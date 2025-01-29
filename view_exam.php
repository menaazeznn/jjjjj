<?php
session_start();
include 'db.php'; // تأكد من أنك قد أنشأت اتصالاً بقاعدة البيانات
include 'header.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// تحقق من أن ID الامتحان موجود في الرابط
if (!isset($_GET['exam_id'])) {
    echo "Invalid exam ID!";
    exit();
}

$examId = $_GET['exam_id'];
$username = $_SESSION['username'];

// استعلام للحصول على بيانات المستخدم
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found!";
    exit();
}
$userId = $user['id'];

// استعلام للحصول على بيانات الامتحان
$stmt = $pdo->prepare('SELECT * FROM exams WHERE id = ?');
$stmt->execute([$examId]);
$exam = $stmt->fetch();

if (!$exam) {
    echo "Exam not found!";
    exit();
}

// استعلام للحصول على الأسئلة
$stmt = $pdo->prepare('SELECT * FROM questions WHERE exam_id = ?');
$stmt->execute([$examId]);
$questions = $stmt->fetchAll();

// معالجة إرسال الإجابات
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userAnswers = $_POST['answer'] ?? [];
    
    // تحقق من أن المستخدم قد اختار إجابة لكل سؤال
    if (count($userAnswers) != count($questions)) {
        echo "Please answer all questions!";
        exit();
    }

    $score = 0;

    foreach ($questions as $question) {
        $questionId = $question['id'];
        $selectedAnswer = $userAnswers[$questionId] ?? null;

        // تأكد من أن الإجابة تم حفظها بالشكل الصحيح
        $stmt = $pdo->prepare('INSERT INTO user_answers (user_id, exam_id, question_id, selected_answer) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $examId, $questionId, $selectedAnswer]);

        // تحقق من الإجابة الصحيحة
        if ($selectedAnswer == $question['correct_answer']) {
            $score++;
        }
    }

    $resultMessage = "Your score is: $score / " . count($questions);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam: <?php echo htmlspecialchars($exam['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
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
        .question-container {
            margin-bottom: 20px;
        }
        .question-text {
            font-weight: bold;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .result-message {
            font-size: 1.2rem;
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Exam: <?php echo htmlspecialchars($exam['title']); ?></h1>
    <p><?php echo htmlspecialchars($exam['description']); ?></p>

    <?php if (!isset($resultMessage)): ?>
        <form method="POST">
            <?php foreach ($questions as $question): ?>
                <div class="question-container">
                    <p class="question-text"><?php echo htmlspecialchars($question['question_text']); ?></p>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <label>
                            <input type="radio" name="answer[<?php echo $question['id']; ?>]" value="<?php echo $i; ?>" 
                                <?php echo isset($userAnswers[$question['id']]) && $userAnswers[$question['id']] == $i ? 'checked' : ''; ?>>
                            <?php echo htmlspecialchars($question["option$i"]); ?>
                        </label><br>
                    <?php endfor; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit">Submit</button>
        </form>
    <?php else: ?>
        <p class="result-message"><?php echo $resultMessage; ?></p>
    <?php endif; ?>
</div>
</body>
</html>
