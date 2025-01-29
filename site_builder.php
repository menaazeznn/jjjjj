<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// استعلام للحصول على معرف المستخدم
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();
$userId = $user['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['siteTitle'];
    $description = $_POST['siteDescription'];
    $type = $_POST['siteType'];

    // إدخال الموقع في قاعدة البيانات
    $stmt = $pdo->prepare('INSERT INTO websites (user_id, title, description, type) VALUES (?, ?, ?, ?)');
    $stmt->execute([$userId, $title, $description, $type]);

    // التوجيه إلى صفحة إدارة الموقع بعد النجاح
    header('Location: manage_website.php');  // يجب أن تكون هذه الصفحة خاصة بإدارة المواقع
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Website</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            font-size: 2.5rem;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.1rem;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 1rem;
            color: #777;
        }
    </style>
</head>
<body>
    <header>
        <h1>Create Your Website</h1>
    </header>
    <div class="container">
        <h2>Website Details</h2>
        <form method="POST">
            <label for="siteTitle">Website Title:</label>
            <input type="text" id="siteTitle" name="siteTitle" required>

            <label for="siteDescription">Description:</label>
            <textarea id="siteDescription" name="siteDescription" required></textarea>

            <label for="siteType">Website Type:</label>
            <select id="siteType" name="siteType" required>
                <option value="blog">Blog</option>
                <option value="portfolio">Portfolio</option>
                <option value="store">Store</option>
            </select>

            <button type="submit">Create Website</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2025 Website Builder. All Rights Reserved.</p>
    </footer>
</body>
</html>
