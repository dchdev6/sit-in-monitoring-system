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
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql1 = "INSERT INTO `students` (`id_number`, `lastName`, `firstName`, `middleName`, `yearLevel`, `password`, `course`, `email`, `address`, `status`)
        VALUES ('$idNum', '$last_Name', '$first_Name', '$middle_Name', '$course_Level', '$passWord', '$course', '$email', '$address', 'TRUE')";
    $sql2 = "INSERT INTO `student_session` (`id_number` , `session`) VALUES ('$idNum', 30)";



    if (mysqli_query($con, $sql1) && mysqli_query($con, $sql2)) {
        return true;
    } else {
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