<?php
session_start();
include 'db.php'; // تأكد من أنك قد أنشأت اتصالاً بقاعدة البيانات

// التحقق من تسجيل الدخول ووجود exam_id في الرابط
if (!isset($_SESSION['username']) || !isset($_GET['exam_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id']; // معرّف المستخدم المخزن في الجلسة
$examId = $_GET['exam_id'];

// استعلام للحصول على إجابات المستخدم مع الأسئلة وتحديد التكرار
$stmt = $pdo->prepare('
    SELECT 
        q.question_text, 
        q.option1, 
        q.option2, 
        q.option3, 
        q.option4, 
        ua.selected_answer, 
        q.correct_answer
    FROM user_answers ua
    JOIN questions q ON ua.question_id = q.id
    WHERE ua.exam_id = ? AND ua.user_id = ?
    GROUP BY q.id
');
$stmt->execute([$examId, $userId]);
$userAnswers = $stmt->fetchAll();

// التحقق من وجود إجابات
if (!$userAnswers) {
    echo "No answers found for this exam.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Answers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .answer-list {
            list-style: none;
            padding: 0;
        }
        .answer-list li {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fafafa;
        }
        .question {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .selected-answer {
            color: #e74c3c;
            font-weight: bold;
        }
        .correct-answer {
            color: green;
            font-weight: bold;
        }
        .option {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Answers for Exam</h2>
        <ul class="answer-list">
            <?php foreach ($userAnswers as $answer): ?>
                <li>
                    <p class="question"><?php echo htmlspecialchars($answer['question_text']); ?></p>
                    <p>Options:</p>
                    <ul>
                        <li class="option">1. <?php echo htmlspecialchars($answer['option1']); ?></li>
                        <li class="option">2. <?php echo htmlspecialchars($answer['option2']); ?></li>
                        <li class="option">3. <?php echo htmlspecialchars($answer['option3']); ?></li>
                        <li class="option">4. <?php echo htmlspecialchars($answer['option4']); ?></li>
                    </ul>
                    <p class="selected-answer">
                        Your answer: 
                        <?php 
                            $selectedOption = "option" . $answer['selected_answer'];
                            echo htmlspecialchars($answer[$selectedOption]);
                        ?>
                    </p>
                    <p class="correct-answer">
                        Correct answer: 
                        <?php 
                            $correctOption = "option" . $answer['correct_answer'];
                            echo htmlspecialchars($answer[$correctOption]);
                        ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
