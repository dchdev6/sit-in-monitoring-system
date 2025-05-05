<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log the call to APIx
error_log("API Student called at " . date('Y-m-d H:i:s'));
error_log("POST: " . print_r($_POST, true));
error_log("FILES: " . print_r($_FILES, true));

// Include backend file
include __DIR__ . '/../backend/backend_student.php';

// Include points functions
include_once __DIR__ . '/../includes/points_functions.php';

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    require_once '../backend/database_connection.php'; // Ensure the database connection is included

    $idNumber = $_POST['id_number'] ?? '';
    $lab = $_POST['sit_lab'] ?? '';
    $feedback = $_POST['feedback_text'] ?? '';

    if (empty($idNumber) || empty($lab) || empty($feedback)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }

    // Save feedback to the database
    $stmt = $conn->prepare("INSERT INTO feedback (id_number, lab, date, message) VALUES (?, ?, NOW(), ?)");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database error: Failed to prepare statement.']);
        exit();
    }

    $stmt->bind_param("sss", $idNumber, $lab, $feedback);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Feedback submitted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }
    $stmt->close();
    exit();
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

/**
 * Request login points for student
 * @param int $student_id The ID number of the student
 * @return bool True if request successful, false otherwise
 */
if (!function_exists('request_login_points')) {
    function request_login_points($student_id) {
        global $conn;
        
        try {
            // Check if student already requested points today
            $check_query = "SELECT * FROM points_requests 
                            WHERE student_id = ? 
                            AND DATE(request_date) = CURDATE() 
                            AND request_type = 'login'";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("i", $student_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Already requested today
                return false;
            }
            
            // Insert new points request
            $query = "INSERT INTO points_requests (student_id, points_amount, request_type, status, request_date) 
                      VALUES (?, 3, 'login', 'pending', NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $student_id);
            $success = $stmt->execute();
            
            return $success;
        } catch (Exception $e) {
            error_log('Error requesting login points: ' . $e->getMessage());
            return false;
        }
    }
}

/**
 * Get points history for a student
 * @param int $student_id The ID number of the student
 * @return array Array of points history records
 */
if (!function_exists('get_points_history')) {
    function get_points_history($student_id) {
        global $conn;
        
        try {
            $query = "SELECT ph.*, pr.request_type 
                      FROM points_history ph
                      LEFT JOIN points_requests pr ON ph.request_id = pr.id
                      WHERE ph.student_id = ?
                      ORDER BY ph.created_at DESC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $history = [];
            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }
            
            return $history;
        } catch (Exception $e) {
            error_log('Error getting points history: ' . $e->getMessage());
            return [];
        }
    }
}

/**
 * Use points for sit-in reservation
 * @param int $student_id The ID number of the student
 * @param int $points_amount Points to use (default 3)
 * @return bool True if successful, false otherwise
 */
if (!function_exists('use_points_for_reservation')) {
    function use_points_for_reservation($student_id, $points_amount = 3) {
        global $conn;
        
        try {
            $conn->begin_transaction();
            
            // Check if student has enough points
            $current_points = get_student_points($student_id);
            if ($current_points < $points_amount) {
                return false;
            }
            
            // Deduct points from student
            $update_query = "UPDATE students SET points = points - ? WHERE id_number = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ii", $points_amount, $student_id);
            $update_stmt->execute();
            
            // Record the transaction in points history
            $history_query = "INSERT INTO points_history 
                             (student_id, points_amount, transaction_type, description, created_at) 
                             VALUES (?, ?, 'deduct', 'Used for sit-in reservation', NOW())";
            $history_stmt = $conn->prepare($history_query);
            $history_stmt->bind_param("ii", $student_id, $points_amount);
            $history_stmt->execute();
            
            // Grant a reservation session (You'll need to implement this part according to your system's design)
            // This is placeholder code - replace with actual reservation creation
            $reservation_query = "INSERT INTO reservations 
                                 (student_id, points_used, status, created_at) 
                                 VALUES (?, ?, 'approved', NOW())";
            $reservation_stmt = $conn->prepare($reservation_query);
            $status = "approved";
            $reservation_stmt->bind_param("iis", $student_id, $points_amount, $status);
            $reservation_stmt->execute();
            
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            error_log('Error using points for reservation: ' . $e->getMessage());
            return false;
        }
    }
}

/**
 * Get leaderboard of students with most points
 * @param int $limit Number of students to return (default 10)
 * @return array Array of top students
 */
if (!function_exists('get_leaderboard')) {
    function get_leaderboard($limit = 10) {
        global $conn;
        
        try {
            $query = "SELECT s.id_number, s.first_name, s.last_name, s.points, p.name as program 
                      FROM students s
                      LEFT JOIN programs p ON s.program_id = p.id
                      ORDER BY s.points DESC
                      LIMIT ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $leaderboard = [];
            while ($row = $result->fetch_assoc()) {
                $leaderboard[] = $row;
            }
            
            return $leaderboard;
        } catch (Exception $e) {
            error_log('Error getting leaderboard: ' . $e->getMessage());
            return [];
        }
    }
}
?>

