<?php
// Start session at the beginning
session_start();

// Enable full error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Log the call to API
error_log("Profile Update API called at " . date('Y-m-d H:i:s'));
error_log("SESSION: " . print_r($_SESSION, true));
error_log("POST: " . print_r($_POST, true));
error_log("FILES: " . print_r($_FILES, true));

// Include backend file
include __DIR__ . '/../backend/backend_student.php';

try {
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
        throw new Exception("Missing ID Number");
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
                throw new Exception("Could not create upload directory");
            }
        }
        
        // Create a unique filename
        $fileName = time() . '_' . basename($_FILES["profile_image"]["name"]);
        $targetFilePath = $imagesDir . "/" . $fileName;
        
        error_log("Target file path: " . $targetFilePath);
        
        // Check for upload errors
        if ($_FILES["profile_image"]["error"] > 0) {
            throw new Exception("Upload error: " . $_FILES["profile_image"]["error"]);
        } 
        else if (!getimagesize($_FILES["profile_image"]["tmp_name"])) {
            throw new Exception("Uploaded file is not an image");
        }
        else if ($_FILES["profile_image"]["size"] > 2097152) {
            throw new Exception("File size too large. Max 2MB");
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
                
                if (!$stmt) {
                    throw new Exception("Database prepare error: " . $con->error);
                }
                
                $stmt->bind_param("ss", $fileName, $idNum);
                if (!$stmt->execute()) {
                    throw new Exception("Database update failed: " . $stmt->error);
                }
                error_log("Database updated with new profile image: " . $fileName);
            } else {
                throw new Exception("Could not save uploaded file");
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

        // Return success response
        echo json_encode([
            'status' => 'success',
            'message' => 'Profile updated successfully!',
            'redirect' => '../view/student/profile.php'
        ]);
    } else {
        throw new Exception("Profile update failed. Database could not be updated.");
    }
} catch (Exception $e) {
    error_log("Profile update exception: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>