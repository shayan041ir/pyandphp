<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آپلود فایل</title>
    <style>
        /* استایل برای بدنه صفحه */
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        /* استایل برای کانتینر فرم آپلود */
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        /* استایل برای تگ h1 */
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        /* استایل برای ورودی‌های فرم */
        input[type="file"], input[type="text"], input[type="submit"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        /* استایل برای دکمه ارسال */
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        /* استایل برای دکمه ارسال در حالت hover */
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        /* استایل برای پیام خطا و موفقیت */
        .message {
            margin: 10px 0;
            color: #d9534f;
        }
        .success {
            color: #5cb85c;
        }
        /* استایل برای لینک بازگشت */
        .back-link {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        /* استایل برای دیو متن */
        .info-text {
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>آپلود فایل</h1> 
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['uploaded_file'])) {
            $file_tmp = $_FILES['uploaded_file']['tmp_name'];
            $file_name = $_FILES['uploaded_file']['name'];

            // آدرس وب سرویس پایتون
            $url = 'http://localhost:5000/upload';

            // تنظیمات cURL
            $ch = curl_init($url);
            $cfile = new CURLFile($file_tmp, $_FILES['uploaded_file']['type'], $file_name);
            $data = array('file' => $cfile);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            // ارسال درخواست و دریافت پاسخ
            $response = curl_exec($ch);
            curl_close($ch);

            // نمایش پاسخ
            $result = json_decode($response, true);
            if (isset($result['result']) && isset($result['filename'])) {
                echo "<div class='message success'>{$result['result']}</div>";
            } else {
                echo "<div class='message'>خطا در آپلود فایل.</div>";
            }
        }
        ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="uploaded_file" required> 
            <input type="submit" value="آپلود"> 
        </form>

        <div class="info-text">
            <?php echo "<div class='message'>نام فایل: {$result['filename']}</div>";?>
        </div>
    </div>
</body>
</html>
