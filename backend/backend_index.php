<?php

require_once 'database_connection.php';

function session_check() {
    return $_SESSION["id_number"] != 0 || $_SESSION["admin_id_number"] != 0;
}

function admin_login($idNum, $passWord) {
    return $idNum == "admin" && $passWord == "admin";
}

function student_login($idNum, $password) {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT students.id_number, students.firstName, students.middleName, students.lastName,
        students.yearLevel, students.email, students.course, students.address, students.profile_image, student_session.session
        FROM students INNER JOIN student_session ON students.id_number = student_session.id_number 
        WHERE students.id_number = ? AND students.password = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $idNum, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION["id_number"] = $user["id_number"];
        $_SESSION["firstName"] = $user["firstName"];
        $_SESSION["lastName"] = $user["lastName"];
        $_SESSION["profile_image"] = $user["profile_image"];
        return $user;
    }
    return null;
}

function student_register($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $passWord, $course, $email, $address) {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql1 = "INSERT INTO students (id_number, lastName, firstName, middleName, yearLevel, password, course, email, address, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'TRUE')";
    $sql2 = "INSERT INTO student_session (id_number, session) VALUES (?, 30)";
    
    $stmt1 = $con->prepare($sql1);
    $stmt1->bind_param("sssssssss", $idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $passWord, $course, $email, $address);
    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param("s", $idNum);
    
    if ($stmt1->execute() && $stmt2->execute()) {
        return true;
    } else {
        return false;
    }
}

function view_announcement() {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM announce ORDER BY announce_id DESC";
    $result = mysqli_query($con, $sql);
    
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>