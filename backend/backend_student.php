<?php

include 'database_connection.php';

function upload_profile_image($file, $idNumber) {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $targetDir = __DIR__ . "/../assets/images/";
    $fileName = basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Debugging: Print the target file path
    error_log("Target File Path: " . $targetFilePath);

    if (!getimagesize($file["tmp_name"])) {
        return "Error: Uploaded file is not an image.";
    }
    if ($file["size"] > 2097152) {
        return "Error: File size too large. Max 2MB.";
    }
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedTypes)) {
        return "Error: Invalid file type.";
    }

    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        // Debugging: Print success message
        error_log("File uploaded successfully: " . $fileName);

        // ✅ Update Database
        $sql = "UPDATE students SET profile_image = ? WHERE id_number = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $fileName, $idNumber);

        if ($stmt->execute()) {
            // Debugging: Confirm database update
            error_log("Database updated with new profile image: " . $fileName);

            // ✅ Update Session
            $_SESSION["profile_image"] = $fileName;
            error_log("Session updated with new profile image: " . $fileName);

            return "Success";
        } else {
            return "Database update failed: " . $stmt->error;
        }
    } else {
        return "Error: Could not move uploaded file.";
    }
}

// ✅ Edit Student Profile (Now includes Profile Image)
function edit_student_student($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $email, $course, $address, $profile_image)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "UPDATE students SET 
                lastName = ?, 
                firstName = ?, 
                middleName = ?, 
                yearLevel = ?, 
                course = ?, 
                email = ?, 
                address = ?, 
                profile_image = ? 
            WHERE id_number = ?";

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        die("Error preparing SQL: " . $con->error);
    }

    $stmt->bind_param("sssssssss", $last_Name, $first_Name, $middle_Name, $course_Level, $course, $email, $address, $profile_image, $idNum);

    if ($stmt->execute()) {
        echo "Profile updated successfully!<br>";
        return true;
    } else {
        die("Database update failed: " . $stmt->error);
    }
}

function loginUser($idNumber, $password) {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM students WHERE id_number = ? AND password = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $idNumber, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        session_start(); // Start the session

        $_SESSION["id_number"] = $row["id_number"];
        $_SESSION["name"] = $row["firstName"] . " " . $row["middleName"] . " " . $row["lastName"];
        $_SESSION["profile_image"] = !empty($row["profile_image"]) ? $row["profile_image"] : "default-profile.jpg";
        $_SESSION["course"] = $row["course"];
        $_SESSION["yearLevel"] = $row["yearLevel"];
        $_SESSION["email"] = $row["email"];
        $_SESSION["address"] = $row["address"];
        $_SESSION["remaining"] = $row["session"];

        // Debugging: Print session data
        error_log("User logged in. Profile image: " . $_SESSION["profile_image"]);

        return true;
    } else {
        return false;
    }
}

function retrieve_student_history($idNumber) {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM student_sit_in INNER JOIN students ON student_sit_in.id_number = students.id_number WHERE student_sit_in.status = 'Finished' AND student_sit_in.id_number = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $idNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    $listPerson = [];
    while ($row = $result->fetch_assoc()) {
        $listPerson[] = $row;
    }
    return $listPerson;
}

function view_announcement() {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM announce ORDER BY announce_id DESC";
    $result = mysqli_query($con, $sql);
    $announcement = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $announcement;
}

function submit_feedback($id, $lab, $message) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-M-d');

    $sql = "INSERT INTO feedback (id_number, lab, date, message) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $id, $lab, $date, $message);
    return $stmt->execute();
}

function submit_reservation($id_number, $purpose, $lab, $pc_number, $time, $date) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "INSERT INTO reservation (reservation_date, reservation_time, pc_number, lab, purpose, id_number, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssss", $date, $time, $pc_number, $lab, $purpose, $id_number);
    return $stmt->execute();
}

function retrieve_reservation_logs($id_number) {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM reservation WHERE id_number = ? ORDER BY reservation_id DESC";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $result = $stmt->get_result();
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function notifications($id_number, $message) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    $sql = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $id_number, $message);
    return $stmt->execute();
}

function retrieve_notification($id_number) {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM notification WHERE id_number = ? ORDER BY notification_id DESC";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $result = $stmt->get_result();
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>