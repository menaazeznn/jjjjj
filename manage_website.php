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

// استعلام للحصول على المواقع التي أنشأها المستخدم
$stmt = $pdo->prepare('SELECT * FROM websites WHERE user_id = ?');
$stmt->execute([$userId]);
$websites = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Websites</title>
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
            max-width: 800px;
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

        .website-list {
            list-style: none;
            padding: 0;
        }

        .website-list li {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .website-list li a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .website-list li a:hover {
            text-decoration: underline;
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
        <h1>Manage Your Websites</h1>
    </header>
    <div class="container">
        <h2>Your Created Websites</h2>

        <?php if (count($websites) > 0): ?>
            <ul class="website-list">
                <?php foreach ($websites as $website): ?>
                    <li>
                        <div>
                            <strong><?php echo htmlspecialchars($website['title']); ?></strong><br>
                            <small><?php echo htmlspecialchars($website['description']); ?></small>
                        </div>
                        <div>
                            <a href="edit_website.php?id=<?php echo $website['id']; ?>">Edit</a>
                            <a href="delete_website.php?id=<?php echo $website['id']; ?>">Delete</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No websites created yet. <a href="create_website.php">Create a new website</a>.</p>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; 2025 Website Builder. All Rights Reserved.</p>
    </footer>
</body>
</html>
