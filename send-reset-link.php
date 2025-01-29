<?php
// تضمين مكتبة PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // أو قم بتضمين ملف autoload.php إذا كنت تستخدم النسخة المحملة يدويًا

// إعداد البيانات
$email = "البريد الإلكتروني للمستخدم";
$reset_link = "https://exams-exams.fwh.is/reset-password.php?token=" . $token; // رابط إعادة تعيين كلمة المرور

// إعداد الرسالة
$subject = "إعادة تعيين كلمة المرور";
$message = "مرحبًا،\n\nيرجى النقر على الرابط التالي لإعادة تعيين كلمة المرور:\n$reset_link";

// إعداد PHPMailer لإرسال البريد عبر SMTP
$mail = new PHPMailer(true);  // إنشاء كائن PHPMailer

try {
    // إعدادات الخادم SMTP
    $mail->isSMTP();  // استخدام SMTP
    $mail->Host = 'smtp.gmail.com';  // خادم SMTP لجيميل
    $mail->SMTPAuth = true;  // تمكين المصادقة
    $mail->Username = 'your-email@gmail.com';  // بريدك الإلكتروني (عنوان Gmail)
    $mail->Password = 'your-app-password';  // كلمة مرور التطبيق (مطلوبة لإرسال البريد عبر Gmail)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // تأمين الاتصال باستخدام STARTTLS
    $mail->Port = 587;  // المنفذ 587 للبريد الآمن عبر STARTTLS

    // المرسل والمستقبل
    $mail->setFrom('your-email@gmail.com', 'Your Name');  // عنوان بريدك والاسم
    $mail->addAddress($email);  // إضافة البريد الإلكتروني للمستقبل

    // محتوى البريد
    $mail->isHTML(false);  // تعيين التنسيق للنص العادي
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // إرسال البريد
    $mail->send();
    echo "تم إرسال رابط إعادة تعيين كلمة السر إلى بريدك الإلكتروني.";
} catch (Exception $e) {
    echo "حدث خطأ أثناء إرسال البريد الإلكتروني: {$mail->ErrorInfo}";
}
?>
