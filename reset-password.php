<?php
include 'db.php';


if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // تحقق من الرمز في قاعدة البيانات
    $sql = "SELECT * FROM reset_tokens WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // عرض نموذج إدخال كلمة المرور الجديدة
        echo '
        <form action="update-password.php" method="POST">
            <label for="password">كلمة المرور الجديدة:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <input type="hidden" name="token" value="' . $token . '">
            <input type="submit" value="تحديث كلمة السر">
        </form>';
    } else {
        echo "الرمز غير صالح أو منتهي الصلاحية.";
    }
}

$conn->close();
?>
