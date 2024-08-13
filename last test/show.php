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
    <title>Show Plaks</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 80%;
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
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <h3>Plak ha</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Plak</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM pelaks";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['plak']; ?></td>
                    </tr>
            <?php }
            } else {
                echo "<tr><td colspan='2'>No records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>