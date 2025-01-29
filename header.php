
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>موقع امتحانات</title>
    <meta name="google-site-verification" content="nnvQHI7tVyc8tk0RPjBClfvVB84uycg_0LyV_cSeI6k" />
     <style>
        /* التنسيق الأساسي للرأس */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        header nav {
            margin-top: 10px;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.2rem;
        }

        header nav a:hover {
            text-decoration: underline;
        }

        .user-info {
            float: right;
            margin-top: -35px;
            margin-right: 20px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <h1>موقع الامتحانات</h1>
        </div>

        <nav>
            <a href="index.php">الصفحة الرئيسية</a>

            <?php if (isset($_SESSION['username'])): ?>
                <a href="upload_exam.php">رفع امتحانات</a>
                <a href="teachers.php">البحث عن مدرسين</a>
            <?php else: ?>
                <a href="login.php">تسجيل الدخول</a>
            <?php endif; ?>
        </nav>

        <?php if (isset($_SESSION['username'])): ?>
            <div class="user-info">
                مرحبًا، <?php echo htmlspecialchars($_SESSION['username']); ?>
            </div>
        <?php endif; ?>
    </header>
</body>
</html>
