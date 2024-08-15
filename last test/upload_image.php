<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $url = 'http://localhost:5000/process_image';

    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    $file_name = basename($_FILES['fileToUpload']['name']);

    // تنظیمات هدر و محتوای POST
    $boundary = uniqid();
    $delimiter = '-------------' . $boundary;
    $post_data = "--" . $delimiter . "\r\n"
        . "Content-Disposition: form-data; name=\"file\"; filename=\"" . $file_name . "\"\r\n"
        . "Content-Type: application/octet-stream\r\n\r\n"
        . file_get_contents($file_tmp) . "\r\n"
        . "--" . $delimiter . "--\r\n";

    $headers = array(
        "Content-Type: multipart/form-data; boundary=" . $delimiter,
        "Content-Length: " . strlen($post_data)
    );

    $options = array(
        'http' => array(
            'header'  => implode("\r\n", $headers),
            'method'  => 'POST',
            'content' => $post_data,
        ),
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        $error = error_get_last();
        echo "Error: " . $error['message'];
    }
    // else {
    //     echo "Number Plate: " . $result;    
    // }    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Plate Number</title>
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
            color: #333;
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
        }

        input[type="file"],
        input[type="submit"] {
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
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            transition: text-decoration 0.3s;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .rtl-text {
            direction: rtl;
            text-align: right;
            unicode-bidi: bidi-override;
            margin-top: 20px;
            font-size: 16px;
            color: green;
        }
        .pelak{
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid gray;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Image</h1>
        <form action="upload_image.php" method="post" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>
        <a class="back-link" href="./show.php">Show Database of Plates</a><br>
        <div class="pelak">
            <?php
            if (isset($result)) {
                echo "<div class='rtl-text'>شماره پلاک: " . htmlspecialchars($result) . "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>