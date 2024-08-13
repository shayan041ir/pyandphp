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
            background-color: #f8f9fa;
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

        if (isset($_FILES['uploaded_file'])) {
            $errors = []; // آرایه برای نگهداری خطاها
            $file_tmp = $_FILES['uploaded_file']['tmp_name']; // مسیر فایل موقت
            $file_type = $_FILES['uploaded_file']['type']; // نوع فایل
            $file_size = $_FILES['uploaded_file']['size']; // اندازه فایل
            
            $file_ext_array = explode('.', $_FILES['uploaded_file']['name']); // تجزیه نام فایل برای گرفتن پسوند
            $file_ext = strtolower(end($file_ext_array)); // دریافت پسوند فایل و تبدیل به حروف کوچک

            $extensions = ["jpeg", "jpg", "png", "pdf"]; // فرمت‌های مجاز

            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "فرمت فایل مجاز نیست. لطفاً یک فایل jpeg، jpg، png یا pdf آپلود کنید."; // خطا در صورت غیرمجاز بودن فرمت
            }

            if ($file_size > 2097152) { // حداکثر سایز 2MB
                $errors[] = 'حجم فایل نباید بیشتر از 2MB باشد.'; // خطا در صورت بزرگتر بودن حجم فایل
            }

            if (empty($errors)) {
                $custom_file_name = $_POST['file_name']; // دریافت نام فایل از فرم
                $file_name = $custom_file_name . '.' . $file_ext; // اضافه کردن پسوند به نام فایل

                // ارسال فایل به سرور Flask
                $uploadUrl = 'http://localhost:5000/upload';
                $cfile = curl_file_create($file_tmp, $file_type, $file_name);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $uploadUrl);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => $cfile]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);

                if ($response === false) {
                    echo '<div class="message">Error: ' . curl_error($ch) . '</div>';
                } else {
                    // دریافت مسیر فایل از سرور Flask
                    $filePath = trim($response);
                    echo "<div class='message success'>فایل با موفقیت آپلود شد.</div>";
                    
                    // نمایش تصویر
                    if (in_array($file_ext, ['jpeg', 'jpg', 'png'])) {
                        echo "<img src='$filePath' alt='Uploaded Image' style='max-width:100%;'>";
                    } else {
                        echo "<div class='info-text'>فایل آپلود شده: <a href='$filePath'>$file_name</a></div>";
                    }
                }

                curl_close($ch);
            } else {
                foreach ($errors as $error) {
                    echo "<div class='message'>$error</div>"; // نمایش پیام‌های خطا
                }
            }
        }
        ?>

        <!-- فرم HTML برای آپلود فایل -->
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="uploaded_file" required> <!-- ورودی انتخاب فایل -->
            <input type="text" name="file_name" placeholder="نام فایل" required> <!-- ورودی نام فایل -->
            <input type="submit" value="آپلود"> <!-- دکمه ارسال -->
        </form>

    </div>
</body>
</html>
