<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pelak";

$conn = new mysqli($servername, $username, $password, $dbname);

?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نمایش پلاک‌ها</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            direction: rtl;
            text-align: right;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2,
        h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        td {
            background-color: #f9f9f9;
        }

        .no-records {
            text-align: center;
            color: #999;
            padding: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>لیست پلاک‌ها</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>پلاک</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM pelaks";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['plak']); ?></td>
                        </tr>
                <?php }
                } else {
                    echo "<tr><td colspan='2' class='no-records'>هیچ رکوردی یافت نشد.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>