<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log the call to APIx
error_log("API Student called at " . date('Y-m-d H:i:s'));
error_log("POST: " . print_r($_POST, true));
error_log("FILES: " . print_r($_FILES, true));

// Include backend file
include __DIR__ . '/../backend/backend_student.php';

// Ensure assets/images directory exists
$assetsDir = __DIR__ . "/../assets";
$imagesDir = $assetsDir . "/images";

if (!file_exists($assetsDir)) {
    mkdir($assetsDir, 0755, true);
    error_log("Created assets directory");
}

if (!file_exists($imagesDir)) {
    mkdir($imagesDir, 0755, true);
    error_log("Created images directory");
}

// Handle profile update
if (isset($_POST["submit"])) {
    error_log("Profile update form submitted");
    
    // Get form data
    $idNum = $_POST['idNumber'] ?? $_SESSION['id_number'] ?? '';
    $last_Name = $_POST['lName'] ?? '';
    $first_Name = $_POST['fName'] ?? '';
    $middle_Name = $_POST['mName'] ?? '';
    $course_Level = $_POST['courseLevel'] ?? '';
    $email = $_POST['email'] ?? '';
    $course = $_POST['course'] ?? '';
    $address = $_POST['address'] ?? '';

    if (empty($idNum)) {
        error_log("ERROR: Missing ID Number");
        $_SESSION['error_message'] = "Missing ID Number. Please try again or contact support.";
        header("Location: ../view/student/profile.php");
        exit;
    }

    // Get existing profile image or use default
    $profile_image = isset($_SESSION["profile_image"]) ? $_SESSION["profile_image"] : "default-profile.jpg";
    error_log("Current profile image: " . $profile_image);
    
    // Handle Profile Image Upload
    if (!empty($_FILES["profile_image"]["name"])) {
        error_log("Processing profile image upload: " . $_FILES["profile_image"]["name"]);
        
        // Make sure the directory exists
        if (!file_exists($imagesDir)) {
            if (!mkdir($imagesDir, 0755, true)) {
                error_log("Failed to create directory: " . $imagesDir);
                $_SESSION['upload_error'] = "Could not create upload directory";
            }
        }
        
        // Create a unique filename
        $fileName = time() . '_' . basename($_FILES["profile_image"]["name"]);
        $targetFilePath = $imagesDir . "/" . $fileName;
        
        error_log("Target file path: " . $targetFilePath);
        
        // Check for upload errors
        if ($_FILES["profile_image"]["error"] > 0) {
            error_log("Upload error code: " . $_FILES["profile_image"]["error"]);
            $_SESSION['upload_error'] = "Upload error: " . $_FILES["profile_image"]["error"];
        } 
        else if (!getimagesize($_FILES["profile_image"]["tmp_name"])) {
            error_log("File is not an image");
            $_SESSION['upload_error'] = "Uploaded file is not an image.";
        }
        else if ($_FILES["profile_image"]["size"] > 2097152) {
            error_log("File is too large");
            $_SESSION['upload_error'] = "File size too large. Max 2MB.";
        }
        else {
            // Move the uploaded file
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
                error_log("File uploaded successfully to: " . $targetFilePath);
                $profile_image = $fileName;
                
                // Update database with new image
                $db = Database::getInstance();
                $con = $db->getConnection();
                
                $sql = "UPDATE students SET profile_image = ? WHERE id_number = ?";
                $stmt = $con->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("ss", $fileName, $idNum);
                    if ($stmt->execute()) {
                        error_log("Database updated with new profile image: " . $fileName);
                    } else {
                        error_log("Database update failed: " . $stmt->error);
                        $_SESSION['upload_error'] = "Database update failed: " . $stmt->error;
                    }
                } else {
                    error_log("Failed to prepare statement: " . $con->error);
                    $_SESSION['upload_error'] = "Database error: " . $con->error;
                }
            } else {
                error_log("Failed to move uploaded file from " . $_FILES["profile_image"]["tmp_name"] . " to " . $targetFilePath);
                $_SESSION['upload_error'] = "Could not save uploaded file.";
            }
        }
    }

    // Update Student Profile
    $updateSuccess = edit_student_student($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $email, $course, $address, $profile_image);
    
    if ($updateSuccess) {
        error_log("Profile updated successfully");
        
        // Update session data
        $_SESSION["profile_image"] = $profile_image;
        $_SESSION["lname"] = $last_Name;
        $_SESSION["fname"] = $first_Name;
        $_SESSION["mname"] = $middle_Name;
        $_SESSION["yearLevel"] = $course_Level;
        $_SESSION["email"] = $email;
        $_SESSION["course"] = $course;
        $_SESSION["address"] = $address;
        $_SESSION['name'] = $first_Name . " " . $middle_Name . " " . $last_Name;

        // Set success message before redirecting
        $_SESSION['success_message'] = "Profile updated successfully!";
        
        // Redirect to profile page
        header("Location: ../view/student/profile.php");
        exit;
    } else {
        error_log("Profile update failed");
        $_SESSION['error_message'] = "Profile update failed";
        header("Location: ../view/student/profile.php");
        exit;
    }
}

// Handle Feedback Submission
if (isset($_POST['submit_feedback'])) {
    $message = $_POST['feedback_text'];
    $id = $_SESSION['id_number'];
    $lab = $_POST['sit_lab'];
    $date = date("Y-m-d");

    if (submit_feedback($id, $lab, $message)) {
        echo "<script>Swal.fire({title: 'Success', text: 'Feedback Submitted', icon: 'success', timer: 2000});</script>";
        notifications($id, "Feedback Confirmed! | $date\nYou have successfully submitted a feedback.");
    }
}

// Handle Reservation Submission
if (isset($_POST['reserve_user'])) {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $id_number = $_POST['id_number'];
    $purpose = $_POST['purpose'];
    $lab = $_POST['lab'];
    $time = $_POST['time'];
    $date = $_POST['date'];

    // Ensure required fields are not empty
    if (empty($id_number) || empty($purpose) || empty($lab) || empty($time) || empty($date)) {
        $_SESSION['error_message'] = "Missing required fields. Please try again.";
        header("Location: ../view/student/reservation.php");
        exit;
    }

    // Generate a random PC number between 1 and 30
    $pc_number = rand(1, 30);
    
    // Connect to database
    $db = Database::getInstance();
    $con = $db->getConnection();

    if (!$con) {
        $_SESSION['error_message'] = "Database connection failed. Please try again later.";
        header("Location: ../view/student/reservation.php");
        exit;
    }

    // Insert reservation
    $sql = "INSERT INTO reservation (reservation_date, reservation_time, pc_number, lab, purpose, id_number, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
    
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        $_SESSION['error_message'] = "System error. Please try again later.";
        header("Location: ../view/student/reservation.php");
        exit;
    }

    $stmt->bind_param("ssssss", $date, $time, $pc_number, $lab, $purpose, $id_number);

    if ($stmt->execute()) {
        // Success - create notification
        $notification_message = "Your reservation for $lab lab on " . date('F j, Y', strtotime($date)) . 
                               " at $time has been submitted and is pending approval.";
        
        // Add notification
        notifications($id_number, $notification_message);
        
        // Set success message and redirect
        $_SESSION['success_message'] = "Your reservation has been submitted successfully! Please wait for admin approval.";
        header("Location: ../view/student/reservation.php");
        exit;
    } else {
        // Error case
        $_SESSION['error_message'] = "Failed to submit reservation. Error: " . $stmt->error;
        header("Location: ../view/student/reservation.php");
        exit;
    }
}
?>

