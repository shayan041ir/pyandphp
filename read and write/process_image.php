<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    // دایرکتوری برای ذخیره فایل‌های آپلود شده
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // نام فایل و مسیر مقصد
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);

    // انتقال فایل آپلود شده به دایرکتوری مقصد
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        // اجرای اسکریپت پایتون و ارسال مسیر فایل
        $command = escapeshellcmd("python ocr_script.py " . escapeshellarg($uploadFile));
        $output = shell_exec($command);

        // نمایش خروجی
        echo "<h1>متن استخراج شده:</h1>";
        echo "<pre>$output</pre>";
    } else {
        echo "آپلود فایل با مشکل مواجه شد.";
    }
} else {
    echo "لطفاً یک فایل تصویر آپلود کنید.";
}
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>آپلود تصویر</title>
</head>
<body>
    <h1>آپلود تصویر و استخراج متن</h1>
    <form action="process_image.php" method="post" enctype="multipart/form-data">
        <label for="image">انتخاب تصویر:</label>
        <input type="file" name="image" id="image" accept="image/*" required>
        <button type="submit">آپلود و پردازش</button>
    </form>
</body>
</html>
