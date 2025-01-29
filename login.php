<?php
session_start();
include 'db.php'; // الاتصال بقاعدة البيانات

// التحقق من إذا كان المستخدم قد قام بتسجيل الدخول مسبقًا عبر الجلسة أو ملفات تعريف الارتباط
if (isset($_SESSION['username']) || (isset($_COOKIE['username']) && isset($_COOKIE['user_id']))) {
    // استعادة بيانات الجلسة من ملفات تعريف الارتباط إذا لم تكن موجودة
    if (!isset($_SESSION['username']) && isset($_COOKIE['username'])) {
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['user_id'] = $_COOKIE['user_id'];
    }
    header('Location: index.php'); // إعادة توجيه المستخدم إلى الصفحة الرئيسية
    exit();
}

// التحقق من أن المستخدم قد أرسل البيانات
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // التحقق من أن البيانات المدخلة غير فارغة
    if (empty($username) || empty($password)) {
        $error = "Please fill all fields!";
    } else {
        // التحقق من بيانات المستخدم في قاعدة البيانات
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // تخزين بيانات الجلسة
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id']; // تخزين user_id في الجلسة

            // إنشاء ملفات تعريف الارتباط لمدة سنة
            setcookie('username', $user['username'], time() + (365 * 24 * 60 * 60), "/"); // اسم المستخدم
            setcookie('user_id', $user['id'], time() + (365 * 24 * 60 * 60), "/"); // معرف المستخدم

            header('Location: index.php'); // إعادة توجيه المستخدم إلى الصفحة الرئيسية
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f2f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4CAF50;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .link {
            text-align: center;
            margin-top: 20px;
        }

        .link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .link a:hover {
            text-decoration: underline;
        }

        /* For mobile responsiveness */
        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>تسجيل الدخول</h2>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">الاسم</label>
                <input type="text" id="username" name="username" required placeholder="ادخل اسمك">
            </div>

            <div class="form-group">
                <label for="password">كلمة السر</label>
                <input type="password" id="password" name="password" required placeholder="ادخل كلمة السر">
            </div>

            <button type="submit">تسجيل الدخول</button>
        </form>

        <div class="link">
            <p>لا تملك حساب <a href="register.php">عمل حساب</a></p>

        </div>
    </div>
</body>
</html>
