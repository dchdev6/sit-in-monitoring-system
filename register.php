<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .register-container {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .register-container h2 {
            margin-bottom: 15px;
            text-align: center;
            font-size: 24px;
            color: #333;
        }
        .register-container label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
        }
        .register-container input,
        .register-container select,
        .register-container button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .register-container button {
            background-color: #555;
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: #333;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
        }
        .form-row .form-group {
            width: 48%;
        }
        .login-link {
            font-size: 13px;
            text-align: center;
            margin-top: 10px;
        }
        .login-link a {
            color: #555;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="register.php" method="post">
        <div class="form-row">
                <div class="form-group">
                    <label for="idno">ID No</label>
                    <input type="text" id="idno" name="idno" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Lastname</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">Firstname</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="midname">Midname</label>
                    <input type="text" id="midname" name="midname">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="course">Course</label>
                    <select id="course" name="course" required>
                        <option value="BSIT">BSIT</option>
                        <option value="BSCS">BSCS</option>
                        <option value="BSIS">BSIS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year_level">Year Level</label>
                    <select id="year_level" name="year_level" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
            </div>
            
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
<?php
require 'db.php'; 

$conn->select_db('sit_in_monitoring_system');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idno = $_POST['idno'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $midname = $_POST['midname'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];
    $username = $_POST['username'];
    $password = $_POST['password']; 

    $stmt = $conn->prepare("INSERT INTO users (USER_ID, USER_NAME, USER_PASS, USER_FNAME, USER_LNAME, USER_MNAME, USER_COURSE, USER_YLEVEL) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $idno, $username, $password, $firstname, $lastname, $midname, $course, $year_level);

    if ($stmt->execute()) {
        echo "<script>alert('User has been created successfully!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: Could not create user.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
