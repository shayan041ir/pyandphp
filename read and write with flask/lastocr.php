<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آپلود فایل</title>
    <style>
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
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="file"], input[type="submit"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            margin: 10px 0;
        }
        .error {
            color: #d9534f;
        }
        .success {
            color: #5cb85c;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            $file_name = $_FILES['uploaded_file']['name'];
            $file_tmp = $_FILES['uploaded_file']['tmp_name'];
            $file_type = $_FILES['uploaded_file']['type'];

            // Check file type
            if (in_array($file_type, $allowed_types)) {
                // Address of the Python Flask web service
                $url = 'http://0.0.0.0:5000/lastocr'; // Update the port if needed

                // cURL settings
                $ch = curl_init($url);
                $cfile = new CURLFile($file_tmp, $file_type, $file_name);
                $data = array('file' => $cfile);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                // Send request and receive response
                $response = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);

                if ($response) {
                    $result = json_decode($response, true);
                    if (isset($result['result']) && isset($result['filename'])) {
                        echo "<h1>متن استخراج شده:</h1>";
                        echo "<div class='message success'>{$result['result']}</div>";
                        echo "<div class='message'>نام فایل: {$result['filename']}</div>";
                    } else {
                        echo "<div class='message error'>خطا در پردازش فایل.</div>";
                    }
                } else {
                    echo "<div class='message error'>خطا در ارتباط با سرور: $error</div>";
                }
            } else {
                echo "<div class='message error'>فرمت فایل نامعتبر است. لطفا یک فایل تصویر JPEG یا PNG آپلود کنید.</div>";
            }
        }
        ?>
        <!-- HTML form for file upload -->
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="uploaded_file" required>
            <input type="submit" value="آپلود">
        </form>
    </div>
</body>
</html>
