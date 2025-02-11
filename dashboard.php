<?php
require 'db.php'; 

$conn->select_db('sit_in_monitoring_system');

// Fetch user information
$user_id = 1; // Replace with the actual user ID from session or login
$stmt = $conn->prepare("SELECT USER_NAME, USER_FNAME, USER_LNAME, USER_COURSE, USER_YLEVEL, USER_EMAIL, USER_ADDRESS, USER_SESSION FROM users WHERE USER_ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $firstname, $lastname, $course, $year_level, $email, $address, $session);
$stmt->fetch();
$stmt->close();
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
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .user-info, .announcement, .rules {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 30%;
        }
        .user-info img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 10px;
        }
        .user-info h3, .announcement h3, .rules h3 {
            text-align: center;
            margin-bottom: 15px;
        }
        .user-info p, .announcement p, .rules p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Home</a>
        <a href="edit_profile.php">Edit Profile</a>
        <a href="history.php">History</a>
        <a href="reservation.php">Reservation</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <div class="user-info">
            <img src="path/to/profile_picture.jpg" alt="Profile Picture">
            <h3>User Information</h3>
            <p><strong>Name:</strong> <?php echo $firstname . ' ' . $lastname; ?></p>
            <p><strong>Course:</strong> <?php echo $course; ?></p>
            <p><strong>Year:</strong> <?php echo $year_level; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Address:</strong> <?php echo $address; ?></p>
            <p><strong>Session:</strong> <?php echo $session; ?></p>
        </div>
        <div class="announcement">
            <h3>Announcement</h3>
            <p>Welcome to the CCS Sit-in Monitoring System!</p>
            <p>Stay tuned for upcoming events and announcements.</p>
        </div>
        <div class="rules">
            <h3>Rules & Regulations</h3>
            <p>Please adhere to the following rules:</p>
            <ul>
                <li>Respect others.</li>
                <li>Maintain cleanliness.</li>
                <li>Follow the schedule.</li>
                <li>Report any issues to the administration.</li>
            </ul>
        </div>
    </div>
</body>
</html>