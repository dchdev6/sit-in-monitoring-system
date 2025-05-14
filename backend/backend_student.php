<?php

include 'database_connection.php';

function upload_profile_image($file, $idNumber) {
    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $db = Database::getInstance();
    $con = $db->getConnection();

    // Make sure directory exists and is writable
    $targetDir = __DIR__ . "/../assets/images/";
    if (!file_exists($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            error_log("Failed to create directory: " . $targetDir);
            return "Error: Could not create upload directory";
        }
    }
    
    // Create a unique filename to prevent overwriting
    $fileName = time() . '_' . basename($file["name"]);
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
            return "Success: " . $fileName; // THIS IS CRITICAL - RETURN WITH SUCCESS: PREFIX
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
    error_log("Edit student function called with: ID=$idNum, Name=$first_Name $last_Name, Image=$profile_image");
    
    try {
        $db = Database::getInstance();
        $con = $db->getConnection();

        if (!$con) {
            error_log("Database connection failed");
            return false;
        }

        $sql = "UPDATE students SET 
                    lastName = ?, 
                    firstName = ?, 
                    middleName = ?, 
                    yearLevel = ?, 
                    course = ?, 
                    email = ?, 
                    address = ?";
                    
        // Only update profile_image if it's set and not empty
        $params = [$last_Name, $first_Name, $middle_Name, $course_Level, $course, $email, $address];
        $types = "sssssss";
        
        if (!empty($profile_image)) {
            $sql .= ", profile_image = ?";
            $params[] = $profile_image;
            $types .= "s";
        }
        
        $sql .= " WHERE id_number = ?";
        $params[] = $idNum;
        $types .= "s";

        $stmt = $con->prepare($sql);
        if (!$stmt) {
            error_log("Error preparing SQL: " . $con->error);
            return false;
        }

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            error_log("Profile updated successfully with SQL: $sql");
            return true;
        } else {
            error_log("Database update failed: " . $stmt->error);
            return false;
        }
    } catch (Exception $e) {
        error_log("Exception in edit_student_student: " . $e->getMessage());
        return false;
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

if (!function_exists('view_announcement')) {
    function view_announcement() {
        $db = Database::getInstance();
        $con = $db->getConnection();

        $sql = "SELECT * FROM announce ORDER BY announce_id DESC";
        $result = mysqli_query($con, $sql);
        $announcement = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $announcement;
    }
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
    
    try {
        $con->begin_transaction();
        
        // Insert reservation
        $sql = "INSERT INTO reservation (reservation_date, reservation_time, pc_number, lab, purpose, id_number, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssss", $date, $time, $pc_number, $lab, $purpose, $id_number);
        $stmt->execute();
        
        // Get student name for notification
        $student_sql = "SELECT firstName, lastName FROM students WHERE id_number = ?";
        $student_stmt = $con->prepare($student_sql);
        $student_stmt->bind_param("s", $id_number);
        $student_stmt->execute();
        $student_result = $student_stmt->get_result();
        $student = $student_result->fetch_assoc();
        $student_name = $student['firstName'] . ' ' . $student['lastName'];
        
        // Add notification for all admins
        $admin_sql = "SELECT id_number FROM admin";
        $admin_result = mysqli_query($con, $admin_sql);
        while ($admin = mysqli_fetch_assoc($admin_result)) {
            $notification_message = "New reservation request from $student_name (ID: $id_number) for $lab lab on " . date('F j, Y', strtotime($date)) . " at $time";
            $notify_sql = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
            $notify_stmt = $con->prepare($notify_sql);
            $notify_stmt->bind_param("ss", $admin['id_number'], $notification_message);
            $notify_stmt->execute();
        }
        
        $con->commit();
        return true;
    } catch (Exception $e) {
        $con->rollback();
        error_log("Error in submit_reservation: " . $e->getMessage());
        return false;
    }
}

function retrieve_student_reservation_logs($id_number) {
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
    try {
        $db = Database::getInstance();
        $con = $db->getConnection();

        // First check if the is_read column exists
        $check_column = "SHOW COLUMNS FROM notification LIKE 'is_read'";
        $result = mysqli_query($con, $check_column);
        
        if (mysqli_num_rows($result) == 0) {
            // Column doesn't exist, add it
            $alter_table = "ALTER TABLE notification ADD COLUMN is_read TINYINT(1) DEFAULT 0";
            if (!mysqli_query($con, $alter_table)) {
                error_log("Failed to add is_read column: " . mysqli_error($con));
            }
        }

        $sql = "SELECT *, COALESCE(is_read, 0) as is_read FROM notification WHERE id_number = ? ORDER BY notification_id DESC";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $id_number);
        $stmt->execute();
        $result = $stmt->get_result();
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log("Error in retrieve_notification: " . $e->getMessage());
        return [];
    }
}

?>