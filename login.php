<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .logo#logo1 {
            filter: brightness(0.7); 
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 400px;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1); 
        }
        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 20px;
            color: #333;
        }
        .login-container label {
            display: block;
            font-size: 14px;
            margin: 10px 0 5px;
            color: #555;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
        .login-container button,
        .login-container a {
            width: 48%;
            padding: 10px;
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
        .login-container button:hover,
        .login-container a:hover {
            background-color: #555;
        }
        .register-button {
            background-color: #555;
        }
        .register-button:hover {
            background-color: #777;
        }
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            margin: 0 -15px; 
        }

        #logo2 {
            display: block;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="logo/1.png" alt="Logo" class="logo" id="logo1">
            <img src="logo/2.png" alt="Logo" class="logo" id="logo2">
        </div>
        <h2>CCS Sit-in Monitoring System</h2>
        <form action="login.php" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <div class="button-container">
                <button type="submit">Login</button>
                <a href="register.php" class="register-button">Register</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php
require 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    error_log("Username: " . $username); // Debugging statement

    $stmt = $conn->prepare("SELECT USER_PASS FROM users WHERE USER_NAME = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($stored_password);
        $stmt->fetch();

        error_log("Stored Password from DB: " . $stored_password); // Debugging statement

        if ($password === $stored_password) {
            header("Location: dashboard.php");
            exit();
        } else {
            error_log("Password verification failed for user: " . $username); // Debugging statement
            echo "<script>alert('Invalid username or password');</script>";
        }
    } else {
        error_log("No user found with username: " . $username); // Debugging statement
        echo "<script>alert('Invalid username or password');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
