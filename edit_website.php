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

// التحقق من وجود الموقع باستخدام معرّف الموقع
if (!isset($_GET['id'])) {
    echo "Website ID is required!";
    exit();
}

$websiteId = $_GET['id'];

// استعلام للحصول على تفاصيل الموقع
$stmt = $pdo->prepare('SELECT * FROM websites WHERE id = ? AND user_id = ?');
$stmt->execute([$websiteId, $userId]);
$website = $stmt->fetch();

if (!$website) {
    echo "Website not found!";
    exit();
}

$websiteTitle = $website['title'];
$websiteDescription = $website['description'];
$websiteType = $website['type'];

// إضافة قسم جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['section_title'])) {
    $sectionTitle = $_POST['section_title'];
    $sectionContent = $_POST['section_content'];

    // إدخال القسم في قاعدة البيانات
    $stmt = $pdo->prepare('INSERT INTO sections (website_id, title, content) VALUES (?, ?, ?)');
    $stmt->execute([$websiteId, $sectionTitle, $sectionContent]);

    echo "<div class='success-message'>Section added successfully!</div>";
}

// استعلام للحصول على الأقسام الخاصة بالموقع
$sections = [];
$stmt = $pdo->prepare('SELECT * FROM sections WHERE website_id = ?');
$stmt->execute([$websiteId]);
$sections = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Website - <?php echo htmlspecialchars($websiteTitle); ?></title>
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
            max-width: 900px;
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

        .section-list {
            margin-top: 20px;
        }

        .section-list ul {
            list-style-type: none;
            padding: 0;
        }

        .section-list li {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .section-list li h3 {
            margin-bottom: 10px;
            font-size: 1.5rem;
            color: #333;
        }

        .section-list li p {
            color: #555;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-top: 20px;
            font-size: 1.2rem;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 1rem;
            color: #777;
        }

        .sidebar {
            width: 25%;
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .sidebar h3 {
            font-size: 1.6rem;
            margin-bottom: 15px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        .sidebar ul li a:hover {
            color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Website - <?php echo htmlspecialchars($websiteTitle); ?></h1>
    </header>

    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="sidebar">
                <h3>Manage Sections</h3>
                <ul>
                    <li><a href="add_section.php?id=<?php echo $websiteId; ?>">Add New Section</a></li>
                    <li><a href="edit_sections.php?id=<?php echo $websiteId; ?>">Edit Existing Sections</a></li>
                    <!-- More links can be added as needed -->
                </ul>
            </div>

            <!-- Main content -->
            <div class="main-content">
                <h2>Website Details</h2>
                <form method="POST">
                    <label for="siteTitle">Website Title:</label>
                    <input type="text" id="siteTitle" name="siteTitle" value="<?php echo htmlspecialchars($websiteTitle); ?>" required>

                    <label for="siteDescription">Description:</label>
                    <textarea id="siteDescription" name="siteDescription" required><?php echo htmlspecialchars($websiteDescription); ?></textarea>

                    <label for="siteType">Website Type:</label>
                    <select id="siteType" name="siteType" disabled>
                        <option value="blog" <?php echo $websiteType == 'blog' ? 'selected' : ''; ?>>Blog</option>
                        <option value="portfolio" <?php echo $websiteType == 'portfolio' ? 'selected' : ''; ?>>Portfolio</option>
                        <option value="store" <?php echo $websiteType == 'store' ? 'selected' : ''; ?>>Store</option>
                    </select>

                    <button type="submit">Update Website</button>
                </form>

                <h3>Existing Sections</h3>
                <div class="section-list">
                    <ul>
                        <?php foreach ($sections as $section): ?>
                            <li>
                                <h3><?php echo htmlspecialchars($section['title']); ?></h3>
                                <p><?php echo nl2br(htmlspecialchars($section['content'])); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Website Builder. All Rights Reserved.</p>
    </footer>
</body>
</html>
