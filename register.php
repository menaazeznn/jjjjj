<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // التحقق من البريد الإلكتروني إذا كان مستخدمًا مسبقًا
    $checkEmail = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $checkEmail->bindParam(':email', $email);
    $checkEmail->execute();

    if ($checkEmail->rowCount() > 0) {  
        $error = "البريد الإلكتروني مسجل بالفعل.";
    } else {
        // إدخال المستخدم
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "حدث خطأ أثناء إنشاء الحساب.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
        }

        .header {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .wrapper {
            display: flex;
            width: 80%;
            max-width: 900px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px; /* ✅ المسافة بين الهيدر والمحتوى */
        }

        .left {
            flex: 1;
            background: #4CAF50;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            text-align: center;
        }

        .left h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .left p {
            font-size: 16px;
            line-height: 1.6;
        }

        .right {
            flex: 1;
            padding: 30px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }

            .left {
                padding: 40px;
            }
        }
    </style>
</head>
<body>


<div class="wrapper">
    

    <div class="right">
        <form action="register.php" method="POST">
            <h2>إنشاء حساب</h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input type="text" id="username" name="username" placeholder="اسم المستخدم" required>
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" placeholder="البريد الإلكتروني" required>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" placeholder="كلمة المرور" required>
            </div>

            <button type="submit">إنشاء حساب</button>
        </form>
    </div>
</div>

</body>
</html>
