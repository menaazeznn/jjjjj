<?php
session_start();
include 'db.php'; // الاتصال بقاعدة البيانات

// التحقق من تسجيل الدخول
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// الحصول على exam_id من الرابط
if (!isset($_GET['exam_id'])) {
    echo "Exam ID not provided.";
    exit();
}

$examId = $_GET['exam_id'];
$userId = $_SESSION['user_id']; // معرف المستخدم الحالي

// جلب تفاصيل الامتحان للتأكد من أن المستخدم هو المنشئ
$stmt = $pdo->prepare('SELECT * FROM exams WHERE id = ? AND creator_id = ?');
$stmt->execute([$examId, $userId]);
$exam = $stmt->fetch();

if (!$exam) {
    echo "You are not authorized to edit this exam.";
    exit();
}

// تحديث بيانات الامتحان إذا تم إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // تحديث جدول الامتحانات
    $updateExamStmt = $pdo->prepare('UPDATE exams SET title = ?, description = ? WHERE id = ?');
    $updateExamStmt->execute([$title, $description, $examId]);

    // تحديث الأسئلة إذا تم تعديلها
    if (isset($_POST['questions'])) {
        foreach ($_POST['questions'] as $questionId => $data) {
            $questionText = $data['text'];
            $correctOption = $data['correct_answer'];
            $options = $data['options'];

            // تحديث نص السؤال والخيارات
            $updateQuestionStmt = $pdo->prepare('UPDATE questions SET question_text = ?, correct_answer = ?, option1 = ?, option2 = ?, option3 = ?, option4 = ? WHERE id = ? AND exam_id = ?');
            $updateQuestionStmt->execute([
                $questionText,
                $correctOption,
                $options[1],
                $options[2],
                $options[3],
                $options[4],
                $questionId,
                $examId
            ]);
        }
    }

    echo "Exam updated successfully.";
    header("Location: index.php");
    exit();
}

// جلب الأسئلة المرتبطة بالامتحان
$questionsStmt = $pdo->prepare('SELECT * FROM questions WHERE exam_id = ?');
$questionsStmt->execute([$examId]);
$questions = $questionsStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .questions {
            margin-top: 20px;
        }

        .question {
            margin-bottom: 20px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Exam</h1>
        <form method="POST">
            <label for="title">Exam Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($exam['title']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($exam['description']); ?></textarea>

            <div class="questions">
                <h3>Questions</h3>
                <?php foreach ($questions as $question): ?>
                    <div class="question">
                        <label for="question_<?php echo $question['id']; ?>">Question <?php echo $question['id']; ?>:</label>
                        <input type="text" id="question_<?php echo $question['id']; ?>" name="questions[<?php echo $question['id']; ?>][text]" value="<?php echo htmlspecialchars($question['question_text']); ?>" required>

                        <div class="options">
                            <h4>Options</h4>
                            <?php
                                // تحديد الخيارات من الأعمدة في جدول questions
                                $options = [
                                    1 => $question['option1'],
                                    2 => $question['option2'],
                                    3 => $question['option3'],
                                    4 => $question['option4']
                                ];
                            ?>
                            <?php foreach ($options as $optionId => $optionText): ?>
                                <div class="option">
                                    <label for="option_<?php echo $optionId; ?>">Option <?php echo $optionId; ?>:</label>
                                    <input type="text" id="option_<?php echo $optionId; ?>" name="questions[<?php echo $question['id']; ?>][options][<?php echo $optionId; ?>]" value="<?php echo htmlspecialchars($optionText); ?>" required>

                                    <label>
                                        <input type="radio" name="questions[<?php echo $question['id']; ?>][correct_answer]" value="<?php echo $optionId; ?>" <?php echo $question['correct_answer'] == $optionId ? 'checked' : ''; ?>> Correct Answer
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
