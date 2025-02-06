<?php
require 'db.php'; 


$conn->select_db('sit_in_monitoring_system');


$sql = "SELECT USER_ID, USER_NAME, USER_FNAME, USER_LNAME, USER_COURSE, USER_YLEVEL FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .dashboard-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 80%;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
        }
        .dashboard-container h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button-container {
            display: flex;
            justify-content: center;
        }
        .dashboard-container a {
            padding: 10px 20px;
            background-color: #333;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            box-sizing: border-box;
        }
        .dashboard-container a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Student Attendance Dashboard</h2>
        <table>
            <thead>
                <tr>
                    <th>ID No</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Course</th>
                    <th>Year Level</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['USER_ID']}</td>
                                <td>{$row['USER_NAME']}</td>
                                <td>{$row['USER_FNAME']}</td>
                                <td>{$row['USER_LNAME']}</td>
                                <td>{$row['USER_COURSE']}</td>
                                <td>{$row['USER_YLEVEL']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="button-container">
            <a href="login.php">Logout</a>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>