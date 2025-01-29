<?php
include 'db.php'; // تأكد من أن ملف الاتصال بقاعدة البيانات جاهز

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_stage = $_POST['stage'];
    $selected_city = $_POST['city'];

    // استعلام لجلب المدرسين حسب المرحلة والمنطقة
    $stmt = $pdo->prepare("
        SELECT username, stages, city, phone
        FROM users 
        WHERE user_type = 'teacher' 
        AND city = :city
    ");
    $stmt->execute([':city' => $selected_city]);

    $teachers = [];
    foreach ($stmt as $row) {
        $stages = json_decode($row['stages'], true); // فك ترميز JSON
        if (isset($stages[$selected_stage]['selected']) && $stages[$selected_stage]['selected'] == 1) {
            $teachers[] = [
                'username' => $row['username'],
                'stage' => $selected_stage,
                'price' => $stages[$selected_stage]['price'],
                'city' => $row['city'],
                'phone' => $row['phone']
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البحث عن مدرس</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group select, .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .results {
            margin-top: 30px;
        }

        .teacher {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .teacher h3 {
            margin: 0 0 10px;
        }

        .teacher p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>البحث عن مدرس</h2>
        <form method="POST">
            <div class="form-group">
                <label for="stage">اختر المرحلة الدراسية</label>
                <select id="stage" name="stage" required>
                    <option value="أولى ابتدائي">أولى ابتدائي</option>
                    <option value="ثانية ابتدائي">ثانية ابتدائي</option>
                    <option value="ثالثة ابتدائي">ثالثة ابتدائي</option>
                    <option value="رابعة ابتدائي">رابعة ابتدائي</option>
                    <option value="خامسة ابتدائي">خامسة ابتدائي</option>
                    <option value="سادسة ابتدائي">سادسة ابتدائي</option>
                    <option value="أولى إعدادي">أولى إعدادي</option>
                    <option value="ثانية إعدادي">ثانية إعدادي</option>
                    <option value="ثالثة إعدادي">ثالثة إعدادي</option>
                    <option value="أولى ثانوي">أولى ثانوي</option>
                    <option value="ثانية ثانوي">ثانية ثانوي</option>
                    <option value="ثالثة ثانوي">ثالثة ثانوي</option>
                </select>
            </div>
            <div class="form-group">
                <label for="city">اختر المحافظة</label>
                <input type="text" id="city" name="city" placeholder="ادخل اسم المحافظة" required>
            </div>
            <button type="submit">بحث</button>
        </form>

        <div class="results">
            <?php if (isset($teachers) && count($teachers) > 0): ?>
                <h3>نتائج البحث:</h3>
                <?php foreach ($teachers as $teacher): ?>
                    <div class="teacher">
                        <h3>الاسم: <?php echo htmlspecialchars($teacher['username']); ?></h3>
                        <p>المرحلة الدراسية: <?php echo htmlspecialchars($teacher['stage']); ?></p>
                        <p>السعر: <?php echo htmlspecialchars($teacher['price']); ?> جنيه</p>
                        <p>المحافظة: <?php echo htmlspecialchars($teacher['city']); ?></p>
                        <p>رقم الهاتف: <?php echo htmlspecialchars($teacher['phone']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php elseif (isset($teachers)): ?>
                <p>لا توجد نتائج مطابقة للبحث.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>