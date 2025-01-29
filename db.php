<?php
$host = 'sql212.infinityfree.com';
$dbname = 'if0_37582390_exams';
$username = 'if0_37582390';  // تأكد من اسم المستخدم وكلمة المرور الخاصين بك
$password = 'Eoxg1V4KqMjn';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
