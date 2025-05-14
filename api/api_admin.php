<?php
include __DIR__ . '/../backend/backend_admin.php';

// Start output buffering to prevent "headers already sent" errors
ob_start();

loginAdmin();
//Object Student
class Student
{
  // Properties (attributes)
  public  $id;
  public  $name;
  public  $records;
  public  $profile_image;
  public  $email;
  public  $yearLevel;
  public  $course;
  public  $address;

  // Constructor method

  public function __construct($id, $name, $records, $profile_image = 'default-profile.jpg', $email = '', $yearLevel = '', $course = '', $address = '')
  {
    $this->id = $id;
    $this->name = $name;
    $this->records = $records;
    $this->profile_image = $profile_image;
    $this->email = $email;
    $this->yearLevel = $yearLevel;
    $this->course = $course;
    $this->address = $address;
  }
}
//Delete Student
if (isset($_POST["deleteStudent"])) {
  $id = $_POST['idNum'];

  if (delete_student($id)) {
    echo '<script>alert("Delete Successful");</script>';
    echo '<script>window.location.href = "Students.php";</script>';
    exit();
  } else {
    echo '<script>alert("Delete Unsuccessful");</script>';
    echo '<script>window.location.href = "Students.php";</script>';
    exit();
  }
}


if (isset($_GET["search"])) {
  $search = $_GET["searchBar"];

  //Search Student Method
  $retrieve = search_student($search);

  if ($retrieve->num_rows > 0) {

    $user = $retrieve->fetch_assoc();
    $record = retrieve_student_session($user['id_number']);

    $student = new Student(
      $user["id_number"], 
      $user["firstName"] . " " . $user["middleName"] . " " . $user["lastName"], 
      $record["session"],
      $user["profile_image"],
      $user["email"],
      $user["yearLevel"],
      $user["course"],
      $user["address"]
    );

    $displayModal = true;
  } else {
    echo '<script>const Toast = Swal.mixin({
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
              }
            });
            Toast.fire({
              icon: "error",
              title: "No student found!"
            });</script>';
  }
}

// get the post records
if (isset($_POST["sitIn"])) {

  $idNum = $_POST['studentID'];
  $purpose = $_POST['purpose'];
  $lab = $_POST['lab'];
  $login = date("h:i:sa");

  $sesions = retrieve_student_session($idNum);


  if ($sesions["session"] == 0) {
    echo '<script>Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Student Session is 0!",
  
    });</script>';
  } else {

    //Check if the student is currently sit in
    $check = check_student_active($idNum);

    if ($check && isset($check["sit_id"]) && $check["sit_id"] != null) {
      echo '<script>const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
        }
      });
      Toast.fire({
        icon: "error",
        title: "Student currently sit-in!"
      });</script>';
    } else {

      if (student_sit_in($idNum, $purpose, $lab, $login)) {
        echo '<script>const Toast = Swal.mixin({
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
              }
            });
            Toast.fire({
              icon: "success",
              title: "Sit-in successfully!"
            });</script>';
      }
    }
  }
}

//Edit Admin

if (isset($_POST["edit"])) {
  $_SESSION["editNum"] = $_POST['idNum'];
  echo '<script>';
  echo 'window.location.href = "Edit.php";';
  echo '</script>';
}

//Logout 



if (isset($_POST["logout"])) {
  // Don't start a session if one is already active
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  $id = $_POST['idNum'];
  $sitId = $_POST['sitId'];
  $log = date("H:i:s");
  $logout = date('Y-m-d');
  $ses = intval($_POST["session"]);  // Convert to integer
  $sitlab = $_POST["sitLab"];
  $newSession = max(0, $ses - 1);
  $awardPoints = isset($_POST['award_points']) && $_POST['award_points'] == '1' ? true : false; // Check the actual value

  if (student_logout($id, $sitId, $log, $logout, $newSession, $awardPoints)) {
      $successMsg = 'Logout successful!';
      if ($awardPoints) {
          $successMsg .= ' 1 point has been awarded to the student.';
      }
      
      echo "<script>
          alert('$successMsg');
          window.location.href = '../view/admin/viewrecords.php';
      </script>";
      exit();
  } else {
      echo "<script>
          alert('Logout failed. Check database logs.');
          window.history.back();
      </script>";
  }
}

// AJAX endpoint for ending a session with optional point awarding
if (isset($_POST['action']) && $_POST['action'] === 'end_session') {
    // Required parameters
    $sessionId = isset($_POST['sessionId']) ? (int)$_POST['sessionId'] : 0;
    $studentId = isset($_POST['studentId']) ? $_POST['studentId'] : '';
    $awardPoints = isset($_POST['awardPoints']) ? (int)$_POST['awardPoints'] : 0;
    
    // Validation
    if (empty($sessionId) || empty($studentId)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Missing required parameters'
        ]);
        exit;
    }
    
    // Get current date and time
    $log = date("H:i:s");
    $logout = date('Y-m-d');
    
    // Get current session count for the student
    $sesData = retrieve_student_session($studentId);
    if (!$sesData) {
        echo json_encode([
            'success' => false, 
            'message' => 'Student not found'
        ]);
        exit;
    }
    
    $currentSession = $sesData['session'] ?? 0;
    $newSession = max(0, $currentSession - 1);
    
    // End the sit-in session with the optional points award
    $result = student_logout($studentId, $sessionId, $log, $logout, $newSession, $awardPoints > 0);
    
    if ($result) {
        $message = 'Session ended successfully';
        if ($awardPoints > 0) {
            $message .= ' and 1 point awarded';
        }
        
        echo json_encode([
            'success' => true,
            'message' => $message,
            'studentId' => $studentId,
            'sessionId' => $sessionId,
            'newSessionCount' => $newSession,
            'pointsAwarded' => $awardPoints > 0
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to end session. Please try again.'
        ]);
    }
    exit;
}

if (isset($_POST["submitEdit"])) {
  $idNum = $_POST['idNumber'];
  $last_Name = $_POST['lName'];
  $first_Name = $_POST['fName'];
  $middle_Name = $_POST['mName'];
  $course_Level = $_POST['courseLevel'];
  $email = $_POST['email'];
  $course = $_POST['course'];
  $address = $_POST['address'];



  if (edit_student_admin($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $email, $course, $address)) {

    echo "<script>Swal.fire({
    title: 'Notification',
    text: 'Edit Profile Successfull',
    icon: 'success',
    showConfirmButton: false,
    timer: 1500
  });</script>";
  } else {

    echo "<script>Swal.fire({
    title: 'Notification',
    text: 'Error! Duplicate ID Number',
    icon: 'error',
    showConfirmButton: false,
    timer: 1500
  });</script>";
  }
}


if (isset($_POST["dateSubmit"])) {
  $date = $_POST["date"];
  $sql = get_date_report(filter_date($date));
} else {
  $sql = get_date_report(reset_date());
}
if (isset($_POST['resetSubmit'])) {

  $sql = get_date_report(reset_date());
}



// Register
if (isset($_POST["submitRegister"])) {
  // Enable detailed error reporting for debugging
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  // Start session if not already started
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  
  // Get and sanitize form data
  $idNum = isset($_POST['idNumber']) ? trim($_POST['idNumber']) : '';
  $last_Name = isset($_POST['lName']) ? trim($_POST['lName']) : '';
  $first_Name = isset($_POST['fName']) ? trim($_POST['fName']) : '';
  $middle_Name = isset($_POST['mName']) ? trim($_POST['mName']) : '';
  $course_Level = isset($_POST['level']) ? trim($_POST['level']) : '';
  $passWord = isset($_POST['password']) ? $_POST['password'] : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $course = isset($_POST['course']) ? trim($_POST['course']) : '';
  $address = isset($_POST['address']) ? trim($_POST['address']) : '';
  
  // Check for required fields
  if (empty($idNum) || empty($last_Name) || empty($first_Name) || 
      empty($course_Level) || empty($passWord) || empty($course)) {
    // If AJAX request, return error as JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      echo json_encode(['success' => false, 'message' => 'Missing required fields']);
      exit;
    } else {
      $_SESSION['registration_error'] = 'All required fields must be filled out.';
      header('Location: ../view/admin/students.php');
      exit;
    }
  }
  
  // Try to add the student
  $result = add_student($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $passWord, $email, $course, $address);
  
  if ($result === true) {
    // Success!
    $_SESSION['registration_success'] = true;
    
    // If AJAX request, return success JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      echo json_encode(['success' => true]);
      exit;
    } else {
      header('Location: ../view/admin/students.php');
      exit;
    }
  } else {
    // Failed
    $errorMessage = is_string($result) ? $result : 'Unable to add student. The ID may already be in use.';
    $_SESSION['registration_error'] = $errorMessage;
    
    // If AJAX request, return error as JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      echo json_encode(['success' => false, 'message' => $errorMessage]);
      exit;
    } else {
      header('Location: ../view/admin/students.php');
      exit;
    }
  }
}


if (isset($_POST['reset_password'])) {
  $new_password = $_POST['new_password'];
  $id = $_SESSION['id_number'];

  if (reset_password($new_password, $id)) {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'Password Reset!',
        icon: 'success',
        showConfirmButton: false,
        timer: 1500
      });</script>";
  } else {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'Error! Password did not change',
        icon: 'error',
        showConfirmButton: false,
        timer: 1500
      });";
  }
}
if (isset($_POST['post_announcement'])) {
  $message = $_POST['announcement_text'];
  $admin_name = $_SESSION['admin_name'];
  $date = date('Y-M-d');

  if (post_announcement($message, $admin_name, $date)) {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'Announcement Posted!',
        icon: 'success',
        showConfirmButton: false,
        timer: 1500
      });</script>";
  }
}

if (isset($_POST['labSubmit'])) {
  $lab_final = "lab_" . $_POST['lab'];
  $current_lab = $_POST['lab'];
  $data = retrieve_pc($lab_final);
}
if (isset($_POST['submitAvail'])) {
  $pc1 = isset($_POST['pc']) ? $_POST['pc'] : [];
  $lab = $_POST['filter_lab'];

  // Only proceed if there are PCs selected
  if (!empty($pc1)) {
    $concat = ""; // Initialize an empty string to store concatenated values

    for ($i = 0; $i < count($pc1); $i++) {
      $concat .= $pc1[$i];

      // Add a comma after each element except for the last one
      if ($i < count($pc1) - 1) {
        $concat .= ",";
      }
    }

    if (available_pc($concat, $lab)) {
      echo "<script>Swal.fire({
          title: 'Notification',
          text: 'PC Available!',
          icon: 'success',
          showConfirmButton: false,
          timer: 1500,
          willClose: () => {
            // Force page reload to refresh the PC status display
            window.location.reload();
          }
        });</script>";
    }
  } else {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'No PCs selected!',
        icon: 'warning',
        showConfirmButton: false,
        timer: 1500
      });</script>";
  }
}
if (isset($_POST['submitDecline'])) {
  $pc1 = isset($_POST['pc']) ? $_POST['pc'] : [];
  $lab = $_POST['filter_lab'];

  // Only proceed if there are PCs selected
  if (!empty($pc1)) {
    $concat = ""; // Initialize an empty string to store concatenated values

    for ($i = 0; $i < count($pc1); $i++) {
      $concat .= $pc1[$i];

      // Add a comma after each element except for the last one
      if ($i < count($pc1) - 1) {
        $concat .= ",";
      }
    }
    if (used_pc($concat, $lab)) {
      echo "<script>Swal.fire({
          title: 'Notification',
          text: 'PC Not Available!',
          icon: 'success',
          showConfirmButton: false,
          timer: 1500,
          willClose: () => {
            // Force page reload to refresh the PC status display
            window.location.reload();
          }
        });</script>";
    }
  } else {
    echo "<script>Swal.fire({
        title: 'Notification',
        text: 'No PCs selected!',
        icon: 'warning',
        showConfirmButton: false,
        timer: 1500
      });</script>";
  }
}

if(isset($_POST['accept_reservation'])){
  $reservation_id = $_POST['reservation_id'];
  $pc_number = $_POST['pc_number'];
  $lab = $_POST['lab'];
  $id_number = $_POST['id_number'];

  if(approve_reservation($reservation_id, $pc_number,$lab,$id_number )){
    echo "<script>Swal.fire({
      title: 'Success',
      text: 'Reservation Approved - PC marked as Used',
      icon: 'success',
      showConfirmButton: false,
      timer: 1500,
      willClose: () => {
        // Force page reload to refresh the PC status display
        window.location.reload();
      }
    });</script>";
  }
}

if(isset($_POST['deny_reservation'])){
  $reservation_id = $_POST['reservation_id'];
  $pc_number = $_POST['pc_number'];
  $lab = $_POST['lab'];
  $id_number = $_POST['id_number'];

  if(decline_reservation($reservation_id, $pc_number,$lab,$id_number )){
    echo "<script>Swal.fire({
      title: 'Notification',
      text: 'Decline Reservation!',
      icon: 'success',
      showConfirmButton: false,
      timer: 1500,
      willClose: () => {
        // Force page reload to refresh the PC status display
        window.location.reload();
      }
    });</script>";
  }
}

/**
 * Get all pending point approval requests
 * @return array Array of pending requests
 */
function get_pending_point_requests() {
    global $conn;
    
    try {
        $query = "SELECT pr.*, s.firstName as first_name, s.lastName as last_name, s.id_number 
                  FROM points_requests pr
                  JOIN students s ON pr.student_id = s.id_number
                  WHERE pr.status = 'pending'
                  ORDER BY pr.request_date DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        
        return $requests;
    } catch (Exception $e) {
        error_log('Error getting pending point requests: ' . $e->getMessage());
        return [];
    }
}

/**
 * Count pending point approval requests
 * @return int Number of pending requests
 */
function count_pending_point_requests() {
    global $conn;
    
    try {
        $query = "SELECT COUNT(*) AS count
                  FROM points_requests 
                  WHERE status = 'pending'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return (int)$row['count'];
        }
        
        return 0;
    } catch (Exception $e) {
        error_log('Error counting pending point requests: ' . $e->getMessage());
        return 0;
    }
}

/**
 * Approve or reject a points request
 * @param int $request_id The ID of the request
 * @param string $status 'approved' or 'rejected'
 * @return bool True if successful, false otherwise
 */
function process_points_request($request_id, $status) {
    global $conn;
    
    try {
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        if (!$con) {
            error_log("Database connection failed in process_points_request");
            return false;
        }
        
        $con->begin_transaction();
        
        // Get request details
        $query = "SELECT * FROM points_requests WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $student_id = $row['student_id'];
            
            // Update request status
            $update_query = "UPDATE points_requests SET status = ? WHERE id = ?";
            $update_stmt = $con->prepare($update_query);
            $update_stmt->bind_param("si", $status, $request_id);
            $update_stmt->execute();
            
            // If approved, add points to student
            if ($status === 'approved') {
                $points_amount = $row['points_amount'];
                
                // Add points to student
                $add_points_query = "UPDATE students SET points = IFNULL(points, 0) + ? WHERE id_number = ?";
                $add_points_stmt = $con->prepare($add_points_query);
                $add_points_stmt->bind_param("is", $points_amount, $student_id);
                $add_points_stmt->execute();
                
                // Record in history
                $history_query = "INSERT INTO points_history 
                                 (student_id, request_id, points_amount, transaction_type, description, created_at) 
                                 VALUES (?, ?, ?, 'add', ?, NOW())";
                $history_stmt = $con->prepare($history_query);
                $transaction_type = "add";
                $description = ucfirst($row['request_type']) . " points request approved";
                $history_stmt->bind_param("iiis", $student_id, $request_id, $points_amount, $description);
                $history_stmt->execute();
                
                // Add notification for student
                $notification_message = "Your points request has been approved. {$points_amount} points have been added to your account.";
                $notify_query = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
                $notify_stmt = $con->prepare($notify_query);
                $notify_stmt->bind_param("ss", $student_id, $notification_message);
                $notify_stmt->execute();
            } else {
                // Add rejection notification
                $notification_message = "Your points request has been rejected.";
                $notify_query = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
                $notify_stmt = $con->prepare($notify_query);
                $notify_stmt->bind_param("ss", $student_id, $notification_message);
                $notify_stmt->execute();
            }
            
            $con->commit();
            
            // Check if points need to be converted to sessions
            if ($status === 'approved') {
                convert_points_to_session($student_id);
            }
            
            return true;
        }
        
        $con->rollback();
        return false;
    } catch (Exception $e) {
        // Rollback transaction on error
        if (isset($con) && $con->ping()) {
            $con->rollback();
        }
        error_log('Error processing points request: ' . $e->getMessage());
        return false;
    }
}

/**
 * Award points manually to a student
 * @param int $student_id The ID of the student
 * @param int $points_amount Points to award
 * @param string $reason Reason for awarding points
 * @return bool True if successful, false otherwise
 */
function award_points_to_student($student_id, $points_amount, $reason) {
    global $conn;
    
    try {
        // First check if the database connection is available
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        if (!$con) {
            error_log("Database connection failed in award_points_to_student");
            return false;
        }
        
        $con->begin_transaction();
        
        // Add points to student
        $add_points_query = "UPDATE students SET points = IFNULL(points, 0) + ? WHERE id_number = ?";
        $add_points_stmt = $con->prepare($add_points_query);
        $add_points_stmt->bind_param("is", $points_amount, $student_id);
        $add_points_stmt->execute();
        
        // Record in history
        $history_query = "INSERT INTO points_history 
                         (student_id, points_amount, transaction_type, description, created_at) 
                         VALUES (?, ?, 'add', ?, NOW())";
        $history_stmt = $con->prepare($history_query);
        $transaction_type = "add";
        $history_stmt->bind_param("iss", $student_id, $points_amount, $reason);
        $history_stmt->execute();
        
        // Add notification for student
        $notification_message = "You have been awarded {$points_amount} points: {$reason}";
        $notify_query = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
        $notify_stmt = $con->prepare($notify_query);
        $notify_stmt->bind_param("ss", $student_id, $notification_message);
        $notify_stmt->execute();
        
        $con->commit();
        
        // Check if points need to be converted to sessions
        convert_points_to_session($student_id);
        
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        if (isset($con) && $con->ping()) {
            $con->rollback();
        }
        error_log('Error awarding points: ' . $e->getMessage());
        return false;
    }
}

/**
 * Post a new announcement
 * @param string $message The announcement message
 * @param string $admin_name The name of the admin posting the announcement
 * @param string $date The date of the announcement
 * @return bool True if successful, false otherwise
 */
function post_announcement($message, $admin_name, $date) {
    global $conn;
    
    try {
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        if (!$con) {
            error_log("Database connection failed in post_announcement");
            return false;
        }
        
        $sql = "INSERT INTO announce (message, admin_name, date) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $message, $admin_name, $date);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error posting announcement: " . $stmt->error);
            return false;
        }
    } catch (Exception $e) {
        error_log('Error posting announcement: ' . $e->getMessage());
        return false;
    }
}

// IMPORTANT: DO NOT ADD CLOSING PHP TAG OR HTML CONTENT BELOW THIS POINT
// Remove all HTML content to prevent headers already sent errors

// Handle semester update
if (isset($_POST["updateSemester"])) {
    $semester = $_POST['semester'];
    $academic_year = $_POST['academic_year'];
    
    if (update_current_semester($semester, $academic_year)) {
        echo json_encode(['success' => true, 'message' => 'Semester updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update semester']);
    }
    exit();
}