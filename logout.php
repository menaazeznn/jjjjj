<?php
session_start();

// تدمير الجلسة وإلغاء جميع المتغيرات الخاصة بها
session_unset();
session_destroy();

// مسح ملفات تعريف الارتباط (الكوكيز) إذا كانت موجودة
if (isset($_COOKIE['user'])) {
    setcookie('user', '', time() - 365 * 24 * 60 * 61, '/'); // تعيين تاريخ انتهاء الكوكيز إلى الماضي
}

if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() -365 * 24 * 60 * 61, '/'); // تعيين تاريخ انتهاء الكوكيز إلى الماضي
}

// إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
header('Location: login.php');
exit();
?>