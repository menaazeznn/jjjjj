<?php
session_start();
include 'db.php'; // تأكد من أنك قد أنشأت اتصالاً بقاعدة البيانات
include 'header.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// الحصول على creator_id من الجلسة
$creatorId = $_SESSION['user_id']; // تأكد من أن قيمة user_id موجودة في الجلسة

// معالجة بيانات الامتحان
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // إضافة الامتحان إلى قاعدة البيانات
    $title = $_POST['title'];
    $description = $_POST['description'];

    // إدخال بيانات الامتحان بما في ذلك creator_id
    $stmt = $pdo->prepare('INSERT INTO exams (title, description, creator_id) VALUES (?, ?, ?)');
    $stmt->execute([$title, $description, $creatorId]); // إضافة creator_id هنا

    // الحصول على معرف الامتحان الذي تم إدخاله
    $examId = $pdo->lastInsertId();

    // إضافة الأسئلة إلى قاعدة البيانات
    $questions = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_answer = $_POST['correct_answer'];

    // إدخال كل سؤال مع خياراته والإجابة الصحيحة (تخزين الإجابة الصحيحة كـ رقم)
    foreach ($questions as $index => $question) {
        $stmt = $pdo->prepare('INSERT INTO questions (exam_id, question_text, option1, option2, option3, option4, correct_answer) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $examId, 
            $question, 
            $option1[$index], 
            $option2[$index], 
            $option3[$index], 
            $option4[$index], 
            $correct_answer[$index] // هنا نقوم بتخزين الرقم الخاص بالخيار الصحيح
        ]);
    }

    echo "<div class='success-message'>Exam created successfully!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Exam</title>
    <style>
        /* Styling for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
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

        form label {
            display: block;
            margin-bottom: 10px;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-top: 20px;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        .question-container {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }

        .options {
            margin-bottom: 10px;
        }

        .options label {
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <h1>Create Exam</h1>
    </header>

    <div class="container">
        <form method="POST">
            <label for="title">Exam Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <div class="questions-section">
                <div class="question-container">
                    <label for="question[]">Question 1:</label>
                    <input type="text" name="question[]" required>

                    <div class="options">
                        <label for="option1[]">Option 1:</label>
                        <input type="text" name="option1[]" required>

                        <label for="option2[]">Option 2:</label>
                        <input type="text" name="option2[]" required>

                        <label for="option3[]">Option 3:</label>
                        <input type="text" name="option3[]" required>

                        <label for="option4[]">Option 4:</label>
                        <input type="text" name="option4[]" required>
                    </div>

                    <label for="correct_answer[]">Correct Answer:</label>
                    <select name="correct_answer[]" required>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                        <option value="4">Option 4</option>
                    </select>
                </div>
            </div>

            <button type="button" id="addQuestionBtn">Add Another Question</button>
            <br><br>
            <button type="submit">Create Exam</button>
        </form>
    </div>

    <script>
        let questionCount = 1;

        document.getElementById('addQuestionBtn').addEventListener('click', function() {
            questionCount++;
            const questionContainer = document.createElement('div');
            questionContainer.classList.add('question-container');
            questionContainer.innerHTML = `
                <label for="question[]">Question ${questionCount}:</label>
                <input type="text" name="question[]" required>

                <div class="options">
                    <label for="option1[]">Option 1:</label>
                    <input type="text" name="option1[]" required>

                    <label for="option2[]">Option 2:</label>
                    <input type="text" name="option2[]" required>

                    <label for="option3[]">Option 3:</label>
                    <input type="text" name="option3[]" required>

                    <label for="option4[]">Option 4:</label>
                    <input type="text" name="option4[]" required>
                </div>

                <label for="correct_answer[]">Correct Answer:</label>
                <select name="correct_answer[]" required>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select>
            `;
            document.querySelector('.questions-section').appendChild(questionContainer);
        });
    </script>
</body>
</html>
