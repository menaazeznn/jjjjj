<?php
session_start();
include 'db.php'; // تأكد من أنك قد أنشأت اتصالاً بقاعدة البيانات

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// استعلام للحصول على جميع الامتحانات
$stmt = $pdo->prepare('SELECT * FROM exams ORDER BY uploaded_at DESC');
$stmt->execute();
$exams = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Exams</title>
    <style>
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

        .exam-list {
            list-style: none;
            padding: 0;
        }

        .exam-list li {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .exam-list h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .exam-list p {
            margin: 10px 0;
        }

        .exam-list a {
            color: #4CAF50;
            text-decoration: none;
        }

        .exam-list a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Exams List</h1>
    </header>

    <div class="container">
        <ul class="exam-list">
            <?php foreach ($exams as $exam): ?>
                <li>
                    <h3><?php echo htmlspecialchars($exam['title']); ?></h3>
                    <p><?php echo htmlspecialchars($exam['description']); ?></p>
                    <a href="<?php echo htmlspecialchars($exam['file_path']); ?>" target="_blank">Download Exam</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
