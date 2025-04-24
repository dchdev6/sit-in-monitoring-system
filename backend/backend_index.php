<?php

require_once 'database_connection.php';


function session_check()
{
    return $_SESSION["id_number"] != 0 || $_SESSION["admin_id_number"] != 0;
}

function admin_login($idNum, $passWord)
{
    return $idNum == "admin" && $passWord == "admin";
}

function student_login($idNum, $password)
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "SELECT * FROM students WHERE id_number = ? AND password = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $idNum, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        error_log("User found in database with ID: " . $row["id_number"] . ", Profile image: " . $row["profile_image"]);
        return $row;
    } else {
        return [
            'id_number' => null,
            'firstName' => '',
            'middleName' => '',
            'lastName' => '',
            'yearLevel' => '',
            'email' => '',
            'course' => '',
            'address' => '',
            'session' => '',
            'profile_image' => ''
        ];
    }
}

function student_register($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $passWord, $course, $email, $address)
{
    error_log("student_register function called with ID: $idNum");
    
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    if (!$con) {
        error_log("Database connection failed in student_register");
        return false;
    }
    
    // First check if the ID already exists
    $check_sql = "SELECT id_number FROM students WHERE id_number = ?";
    $check_stmt = $con->prepare($check_sql);
    
    if (!$check_stmt) {
        error_log("Prepare statement failed: " . $con->error);
        return false;
    }
    
    $check_stmt->bind_param("s", $idNum);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // ID already exists
        error_log("ID $idNum already exists in database");
        $check_stmt->close();
        return false;
    }
    
    $check_stmt->close();
    error_log("ID $idNum is available for registration");
    
    // Using prepared statements for security
    $sql1 = "INSERT INTO `students` (`id_number`, `lastName`, `firstName`, `middleName`, `yearLevel`, `password`, `course`, `email`, `address`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'TRUE')";
    $stmt1 = $con->prepare($sql1);
    
    if (!$stmt1) {
        error_log("Prepare statement 1 failed: " . $con->error);
        return false;
    }
    
    $stmt1->bind_param("ssssissss", $idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $passWord, $course, $email, $address);
    
    $sql2 = "INSERT INTO `student_session` (`id_number`, `session`) VALUES (?, 30)";
    $stmt2 = $con->prepare($sql2);
    
    if (!$stmt2) {
        error_log("Prepare statement 2 failed: " . $con->error);
        $stmt1->close();
        return false;
    }
    
    $stmt2->bind_param("s", $idNum);
    
    // Start transaction
    $con->begin_transaction();
    error_log("Starting database transaction for registration");
    
    try {
        // Execute first query
        if (!$stmt1->execute()) {
            throw new Exception("Failed to add student: " . $stmt1->error);
        }
        error_log("Successfully inserted student record");
        
        // Execute second query
        if (!$stmt2->execute()) {
            throw new Exception("Failed to set session: " . $stmt2->error);
        }
        error_log("Successfully inserted student session record");
        
        // Commit if everything worked
        $con->commit();
        error_log("Transaction committed successfully");
        $stmt1->close();
        $stmt2->close();
        return true;
    } catch (Exception $e) {
        // Roll back on error
        $con->rollback();
        error_log("Registration transaction error: " . $e->getMessage());
        if ($stmt1) $stmt1->close();
        if ($stmt2) $stmt2->close();
        return false;
    }
}

function view_announcement()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM announce ORDER BY announce_id desc";

    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $announcement = [];
        while ($row = mysqli_fetch_array($result)) {
            $announcement[] = $row;
        }
    }
    return $announcement;
}