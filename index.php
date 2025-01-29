<?php
session_start();
include 'db.php'; // الاتصال بقاعدة البيانات
include 'header.php'; // إذا كنت تستخدم ملف Header مشترك

// التحقق من تسجيل الدخول
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    // إعادة التوجيه إذا لم يكن المستخدم مسجلاً الدخول
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id']; // جلب user_id من الجلسة

// جلب قائمة الامتحانات من قاعدة البيانات
$stmt = $pdo->prepare('SELECT * FROM exams ORDER BY uploaded_at DESC');
$stmt->execute();
$exams = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الامتحانات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .exam-list {
            list-style: none;
            padding: 0;
        }

        .exam-list li {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .exam-list h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .exam-list p {
            margin: 10px 0;
        }

        .exam-buttons {
            display: flex;
            gap: 10px;
        }

        .exam-buttons a {
            text-decoration: none;
            padding: 8px 12px;
            color: white;
            background-color: #4CAF50;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .exam-buttons a:hover {
            background-color: #45a049;
        }

        .copy-button {
            background-color: #007bff;
        }

        .copy-button:hover {
            background-color: #0056b3;
        }

        .no-exams {
            text-align: center;
            font-size: 1.2rem;
            color: #ff0000;
        }
    </style>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('تم نسخ الرابط بنجاح!');
            }).catch(err => {
                alert('حدث خطأ أثناء نسخ الرابط!');
            });
        }
    </script>
</head>
<body>
    <header>
        <h2>تأكد من الأسئلة والإجابات من مصادرك لأنها قد تحتوي على أخطاء</h2>
        <h1>الامتحانات المتاحة</h1>
    </header>
    <div class="container">
        <?php if (empty($exams)): ?>
            <p class="no-exams">لا توجد امتحانات متاحة حالياً.</p>
        <?php else: ?>
            <ul class="exam-list">
                <?php foreach ($exams as $exam): ?>
                    <li>
                        <div>
                            <h3><?php echo htmlspecialchars($exam['title']); ?></h3>
                            <p><?php echo htmlspecialchars($exam['description']); ?></p>
                        </div>
                        <div class="exam-buttons">
                            <!-- زر فتح الامتحان -->
                            <a href="view_exam.php?exam_id=<?php echo $exam['id']; ?>&user_id=<?php echo $userId; ?>">فتح الامتحان</a>
                            <!-- زر عرض الإجابات -->
                            <a href="view_results.php?exam_id=<?php echo $exam['id']; ?>&user_id=<?php echo $userId; ?>">عرض الإجابات</a>
                            <!-- زر التعديل يظهر فقط إذا كان المستخدم هو منشئ الامتحان -->
                            <?php if ($exam['creator_id'] == $userId): ?>
                                <a href="edit_exam.php?exam_id=<?php echo $exam['id']; ?>">تعديل الامتحان</a>
                            <?php endif; ?>
                            <!-- زر نسخ الرابط -->
                            <a href="#" class="copy-button" onclick="copyToClipboard('<?php echo 'https://exams-exams.fwh.is/view_exam.php?exam_id=' . $exam['id']; ?>')">نسخ الرابط</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>