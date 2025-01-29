<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = $_POST['token'];

    // تحديث كلمة السر في قاعدة البيانات
    $sql = "SELECT email FROM reset_tokens WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // تحديث كلمة المرور للمستخدم
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $email);
        $stmt->execute();

        // حذف الرمز من جدول reset_tokens
        $sql = "DELETE FROM reset_tokens WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "تم تحديث كلمة السر بنجاح!";
    } else {
        echo "الرمز غير صالح أو منتهي الصلاحية.";
    }
}

$conn->close();
?>
