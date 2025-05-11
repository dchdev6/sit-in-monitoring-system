<?php
// Prevent duplicate inclusion
if (defined('BACKEND_ADMIN_INCLUDED')) {
    return;
}
define('BACKEND_ADMIN_INCLUDED', true);

require_once 'database_connection.php';

// Only define the function if it doesn't already exist
if (!function_exists('loginAdmin')) {
    function loginAdmin()
    {
        if ($_SESSION['admin_id_number'] == 1 && !isset($_SESSION['success_toast_displayed'])) {
            echo '<script>
                        const Toast = Swal.mixin({
                          toast: true,
                          position: "top-start",
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
                          title: "Logged In!"
                        });
                      </script>';

            $_SESSION['success_toast_displayed'] = true;
        } else if ($_SESSION['admin_id_number'] == null) {
            echo '<script>window.location.href = "../../Login.php";</script>';
        }
    }
}

//Number of students to Dashboard Admin

function retrieve_students_dashboard()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = " SELECT count(id_number) as id from students where status = 'TRUE';";
    $result = mysqli_query($con, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    return $user['id'];
}
function retrieve_current_sit_in_dashboard()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(sit_id) as id from student_sit_in where status = 'Active';";
    $result = mysqli_query($con, $sql);
    $sit = mysqli_fetch_array($result, MYSQLI_ASSOC);

    return $sit['id'];
}
function retrieve_total_sit_in_dashboard()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(sit_id) as id from student_sit_in ;";
    $result = mysqli_query($con, $sql);
    $total = mysqli_fetch_array($result, MYSQLI_ASSOC);

    return $total['id'];
}

function delete_student($idNum)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "UPDATE `students` SET `status` = 'FALSE' WHERE `id_number` = '$idNum'";

    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        return false;
    }
}

// Make sure this function is working properly
function retrieve_students()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    // Add debugging
    error_log("Retrieving all students from database");
    
    $sql = "SELECT s.id_number, s.lastName, s.firstName, s.middleName, s.yearLevel, s.email, s.course, s.address, ss.session 
            FROM students s
            LEFT JOIN student_session ss ON s.id_number = ss.id_number
            ORDER BY s.id_number";
            
    $result = $con->query($sql);
    
    if (!$result) {
        error_log("SQL error in retrieve_students: " . $con->error);
        return [];
    }
    
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    
    error_log("Retrieved " . count($students) . " students");
    return $students;
}

function search_student($search)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT *, IFNULL(profile_image, 'default-profile.jpg') as profile_image FROM students WHERE (id_number = ? OR lastName = ? OR firstName = ?) AND `status` = 'TRUE'";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    return $result = $stmt->get_result();
}
function retrieve_student_session($id)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql1 = "SELECT * FROM student_session WHERE id_number = ?";
    $stmt1 = $con->prepare($sql1);
    $stmt1->bind_param("s", $id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    return $record = $result1->fetch_assoc();
}

function check_student_active($idNum)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    // Use prepared statement to prevent SQL injection
    $active = "SELECT * FROM student_sit_in WHERE id_number = ? AND status = 'Active'";
    $stmt = $con->prepare($active);
    
    if (!$stmt) {
        error_log("Prepare statement failed in check_student_active: " . $con->error);
        return null;
    }
    
    $stmt->bind_param("s", $idNum);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    } else {
        $stmt->close();
        return null;
    }
}
function student_sit_in($idNum, $purpose, $lab, $login)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sit = "INSERT INTO `student_sit_in` (`id_number`, `sit_purpose`, `sit_lab`, `sit_login` , `status`)
        VALUES ('$idNum', '$purpose', '$lab', '$login' , 'Active')";

    if (mysqli_query($con, $sit)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if a student has 3 or more points and convert them to sessions
 * @param string $student_id The student ID
 * @return bool True if conversion happened, false otherwise
 */
function convert_points_to_session($student_id) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    try {
        // Begin transaction
        $con->begin_transaction();
        
        // Get current points
        $points_query = "SELECT points FROM students WHERE id_number = ? FOR UPDATE";
        $points_stmt = $con->prepare($points_query);
        $points_stmt->bind_param("s", $student_id);
        $points_stmt->execute();
        $points_result = $points_stmt->get_result();
        
        if ($row = $points_result->fetch_assoc()) {
            $current_points = (int)$row['points'];
            
            // Check if student has at least 3 points
            if ($current_points >= 3) {
                // Get current sessions
                $sessions_query = "SELECT session FROM student_session WHERE id_number = ? FOR UPDATE";
                $sessions_stmt = $con->prepare($sessions_query);
                $sessions_stmt->bind_param("s", $student_id);
                $sessions_stmt->execute();
                $sessions_result = $sessions_stmt->get_result();
                
                if ($sessions_row = $sessions_result->fetch_assoc()) {
                    $current_sessions = (int)$sessions_row['session'];
                    
                    // Calculate maximum sessions that can be added without exceeding 30
                    $max_sessions_to_add = 30 - $current_sessions;
                    if ($max_sessions_to_add <= 0) {
                        // Student already has maximum sessions
                        $con->commit();
                        return false;
                    }
                    
                    // Calculate how many sessions to add (each 3 points = 1 session)
                    $potential_sessions_to_add = floor($current_points / 3);
                    $sessions_to_add = min($potential_sessions_to_add, $max_sessions_to_add);
                    $points_to_convert = $sessions_to_add * 3;
                    $remaining_points = $current_points - $points_to_convert;
                    
                    // Only proceed if we have points to convert
                    if ($sessions_to_add > 0) {
                        // Update points
                        $update_points_query = "UPDATE students SET points = ? WHERE id_number = ?";
                        $update_points_stmt = $con->prepare($update_points_query);
                        $update_points_stmt->bind_param("is", $remaining_points, $student_id);
                        $update_points_stmt->execute();
                        
                        // Update sessions
                        $new_sessions = $current_sessions + $sessions_to_add;
                        $update_sessions_query = "UPDATE student_session SET session = ? WHERE id_number = ?";
                        $update_sessions_stmt = $con->prepare($update_sessions_query);
                        $update_sessions_stmt->bind_param("is", $new_sessions, $student_id);
                        $update_sessions_stmt->execute();
                        
                        // Record in points history if the table exists
                        $historyTableCheck = $con->query("SHOW TABLES LIKE 'points_history'");
                        if ($historyTableCheck->num_rows > 0) {
                            $history_query = "INSERT INTO points_history 
                                            (student_id, points_amount, transaction_type, description, created_at) 
                                            VALUES (?, ?, 'convert', ?, NOW())";
                            $history_stmt = $con->prepare($history_query);
                            $description = "Converted {$points_to_convert} points to {$sessions_to_add} sessions";
                            $history_stmt->bind_param("sis", $student_id, $points_to_convert, $description);
                            $history_stmt->execute();
                        }
                        
                        // Add notification for student
                        $notification_message = "Your {$points_to_convert} points have been converted to {$sessions_to_add} additional lab sessions.";
                        if ($new_sessions == 30) {
                            $notification_message .= " You've reached the maximum of 30 sessions.";
                        }
                        $notify_query = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
                        $notify_stmt = $con->prepare($notify_query);
                        $notify_stmt->bind_param("ss", $student_id, $notification_message);
                        $notify_stmt->execute();
                        
                        // Commit transaction
                        $con->commit();
                        return true;
                    }
                }
            }
        }
        
        // If we got here, either the student doesn't have enough points or something went wrong
        $con->commit();
        return false;
    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollback();
        error_log("Points conversion error: " . $e->getMessage());
        return false;
    }
}

// Now modify the student_logout function to check for point conversion after awarding points
function student_logout($id, $sitId, $log, $logout, $newSession, $awardPoints = false)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    // Ensure session count doesn't exceed 30
    $newSession = min(30, max(0, (int)$newSession));

    $sql = "UPDATE `student_sit_in` SET `status` = 'Finished', `sit_logout` = '$log', `sit_date` = '$logout' WHERE `id_number` = '$id' AND `sit_id` = '$sitId'";
    $sql1 = "UPDATE `student_session` SET `session` = '$newSession' WHERE `id_number` = '$id'";

    // Begin transaction for consistent database update
    $con->begin_transaction();
    
    try {
        // Update sit-in status
        if (!mysqli_query($con, $sql)) {
            throw new Exception("Error updating sit-in: " . mysqli_error($con));
        }
        
        // Update session count
        if (!mysqli_query($con, $sql1)) {
            throw new Exception("Error updating session: " . mysqli_error($con));
        }
        
        $pointsAwarded = false;
        
        // Award points if specified
        if ($awardPoints) {
            // Check if points column exists in students table
            $checkPointsColumn = $con->query("SHOW COLUMNS FROM students LIKE 'points'");
            if ($checkPointsColumn->num_rows == 0) {
                // Add points column if it doesn't exist
                $con->query("ALTER TABLE students ADD COLUMN points INT DEFAULT 0");
            }
            
            // Award 1 point to the student
            $award_points_sql = "UPDATE students SET points = IFNULL(points, 0) + 1 WHERE id_number = '$id'";
            if (!mysqli_query($con, $award_points_sql)) {
                throw new Exception("Error awarding points: " . mysqli_error($con));
            }
            
            $pointsAwarded = true;
            
            // Record in points history if the table exists
            $historyTableCheck = $con->query("SHOW TABLES LIKE 'points_history'");
            if ($historyTableCheck->num_rows > 0) {
                $history_query = "INSERT INTO points_history 
                                 (student_id, points_amount, transaction_type, description, created_at) 
                                 VALUES ('$id', 1, 'add', 'Awarded for completing sit-in session', NOW())";
                mysqli_query($con, $history_query);
            }
            
            // Add notification for student
            $notification_message = "You have been awarded 1 point for completing your sit-in session.";
            $notify_query = "INSERT INTO notification (id_number, message) VALUES ('$id', '$notification_message')";
            mysqli_query($con, $notify_query);
        }
        
        // Commit transaction if all operations are successful
        $con->commit();
        
        // Check if points need to be converted to sessions
        // We do this in a separate transaction to avoid making the main one too complex
        if ($pointsAwarded) {
            convert_points_to_session($id);
        }
        
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollback();
        error_log("Student logout error: " . $e->getMessage());
        return false;
    }
}

function retrieve_edit_student($idNum)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM students WHERE id_number = '$idNum'";
    $result = mysqli_query($con, $sql);
    return $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
}
function edit_student_admin($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $email, $course, $address)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "UPDATE `students` SET  `lastName` = '$last_Name', `firstName`= '$first_Name', `middleName`= '$middle_Name', `yearLevel`= '$course_Level', `course` = '$course', `email` = '$email', `address`= '$address' WHERE `id_number` = '$idNum'";

    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        return false;
    }
}

function retrieve_sit_in()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    // Initialize listPerson as an empty array
    $listPerson = [];

    $sqlTable = "SELECT student_sit_in.sit_id, students.id_number , students.firstName , students.middleName, students.lastName ,student_sit_in.sit_purpose, student_sit_in.sit_lab , student_session.session, student_sit_in.status FROM students INNER JOIN student_session ON students.id_number = student_session.id_number INNER JOIN student_sit_in ON student_sit_in.id_number = student_session.id_number
        WHERE student_sit_in.status = 'Active';";
    $result = mysqli_query($con, $sqlTable);
    
    // Check if result is valid and has rows
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $listPerson[] = $row;
        }
    }
    
    return $listPerson;
}

function retrieve_current_sit_in()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT student_sit_in.sit_id, students.id_number, students.firstName, students.lastName,
        student_sit_in.sit_purpose, student_sit_in.sit_lab, student_sit_in.sit_login, 
        student_sit_in.sit_logout, student_sit_in.sit_date, student_sit_in.status
        FROM students 
        INNER JOIN student_sit_in ON students.id_number = student_sit_in.id_number
        WHERE student_sit_in.status IN ('Active', 'Finished')
        ORDER BY student_sit_in.sit_date DESC, student_sit_in.sit_login DESC";

    $result = mysqli_query($con, $sql);
    $listPerson = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $listPerson[] = $row;
        }
    } else {
        echo "Error: " . mysqli_error($con); // Debugging
    }

    return $listPerson;
}

function filter_date($date)
{

    return " SELECT student_sit_in.sit_id, students.id_number, students.firstName,students.lastName,
        student_sit_in.sit_purpose, student_sit_in.sit_lab , student_sit_in.sit_login,
        student_sit_in.sit_logout,student_sit_in.sit_date, student_sit_in.status FROM
        students INNER JOIN student_sit_in ON students.id_number = student_sit_in.id_number
        INNER JOIN student_session ON student_sit_in.id_number = student_session.id_number WHERE student_sit_in.status = 'Finished' AND student_sit_in.sit_date = '$date' ;";
}
function reset_date()
{
    return " SELECT student_sit_in.sit_id, students.id_number, students.firstName,students.lastName,
        student_sit_in.sit_purpose, student_sit_in.sit_lab , student_sit_in.sit_login,
        student_sit_in.sit_logout,student_sit_in.sit_date, student_sit_in.status FROM
        students INNER JOIN student_sit_in ON students.id_number = student_sit_in.id_number
        INNER JOIN student_session ON student_sit_in.id_number = student_session.id_number WHERE student_sit_in.status = 'Finished';";
}

function get_date_report($sql)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $listPerson = [];
        while ($row = mysqli_fetch_array($result)) {
            $listPerson[] = $row;
        }
    }
    return $listPerson;
}

function add_student($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $passWord, $email, $course, $address)
{
    error_log("add_student function called with ID: $idNum, Name: $first_Name $last_Name");
    
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    if (!$con) {
        error_log("Database connection failed in add_student");
        return "Database connection failed";
    }
    
    // First check if the ID already exists
    $check_sql = "SELECT id_number FROM students WHERE id_number = ?";
    $check_stmt = $con->prepare($check_sql);
    
    if (!$check_stmt) {
        error_log("Prepare check statement failed: " . $con->error);
        return "Database error: " . $con->error;
    }
    
    $check_stmt->bind_param("s", $idNum);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // ID already exists
        error_log("ID $idNum already exists in database");
        $check_stmt->close();
        return "Student ID already exists";
    }
    
    $check_stmt->close();
    error_log("ID $idNum is available for registration");
    
    // Using prepared statements for security
    $sql1 = "INSERT INTO `students` (`id_number`, `lastName`, `firstName`, `middleName`, `yearLevel`, `password`, `course`, `email`, `address`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'TRUE')";
    $stmt1 = $con->prepare($sql1);
    
    if (!$stmt1) {
        error_log("Prepare statement 1 failed: " . $con->error);
        return "Database error: " . $con->error;
    }
    
    $stmt1->bind_param("ssssissss", $idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $passWord, $course, $email, $address);
    
    // Set initial session count (maximum 30)
    $initial_session_count = 30;
    $sql2 = "INSERT INTO `student_session` (`id_number`, `session`) VALUES (?, ?)";
    $stmt2 = $con->prepare($sql2);
    
    if (!$stmt2) {
        error_log("Prepare statement 2 failed: " . $con->error);
        $stmt1->close();
        return "Database error: " . $con->error;
    }
    
    $stmt2->bind_param("si", $idNum, $initial_session_count);
    
    // Start transaction
    $con->begin_transaction();
    error_log("Starting database transaction for registration");
    
    try {
        // Execute first query
        if (!$stmt1->execute()) {
            error_log("First statement execution failed: " . $stmt1->error);
            $con->rollback();
            $stmt1->close();
            $stmt2->close();
            return "Failed to register student: " . $stmt1->error;
        }
        error_log("Successfully inserted student record");
        
        // Execute second query
        if (!$stmt2->execute()) {
            error_log("Second statement execution failed: " . $stmt2->error);
            $con->rollback();
            $stmt1->close();
            $stmt2->close();
            return "Failed to set student sessions: " . $stmt2->error;
        }
        error_log("Successfully inserted student session");
        
        // If both queries succeeded, commit the transaction
        $con->commit();
        error_log("Transaction committed successfully");
        
        $stmt1->close();
        $stmt2->close();
        
        return true;
    } catch (Exception $e) {
        error_log("Exception in add_student transaction: " . $e->getMessage());
        
        // Roll back the transaction in case of an error
        $con->rollback();
        $stmt1->close();
        $stmt2->close();
        
        return "An error occurred: " . $e->getMessage();
    }
}
function reset_password($new_password,$id){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "UPDATE `students` SET `password` = '$new_password' WHERE `id_number` = '$id'";
    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        return false;
    }
}

if (!function_exists('view_announcement')) {
    function view_announcement(){
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
}

function view_feedback() {
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT id_number, lab, date, message FROM feedback ORDER BY feedback_id DESC";
    $result = mysqli_query($con, $sql);

    $feedback = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feedback[] = [
                'id_number' => $row['id_number'] ?? 'N/A',
                'lab' => $row['lab'] ?? 'N/A',
                'date' => $row['date'] ?? 'N/A',
                'message' => $row['message'] ?? 'N/A'
            ];
        }
    }
    return $feedback;
}

function retrieve_pc($lab){
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    // Add error logging to debug issues
    error_log("retrieve_pc called with lab parameter: " . $lab);
    
    $sql = "SELECT pc_id, `$lab` as lab2 FROM student_pc";

    $result = mysqli_query($con, $sql);
    
    // Initialize pc array
    $pc = [];
    
    // Check if query was successful
    if ($result === false) {
        error_log("SQL error in retrieve_pc: " . mysqli_error($con));
        return $pc; // Return empty array on query failure
    }
    
    // Check if there are rows to fetch
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $pc[] = $row;
        }
    }
    
    return $pc;
}

function available_pc($concat,$lab){
    $db = Database::getInstance();
    $con = $db->getConnection();
    $concat1 = "(" . $concat . ")";

    $sql = "UPDATE `student_pc` SET `$lab` = '1' WHERE `pc_id` IN $concat1;";
    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        // Log or display MySQL errors
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
        return false;
    }
}
function used_pc($concat, $lab)
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $concat1 = "(" . $concat . ")";

    $sql = "UPDATE `student_pc` SET `$lab` = '0' WHERE `pc_id` IN $concat1;";
    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        // Log or display MySQL errors
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
        return false;
    }
}

function retrieve_c_programming(){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'C-Programming'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}
function retrieve_c_sharp_programming()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'C# Programming'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}
function retrieve_java_programming()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'Java Programming'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}
function retrieve_asp_programming()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'ASP.Net Programming'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}
function retrieve_php_programming()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'Php Programming'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}

function retrieve_first(){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(yearLevel) as year from students where yearLevel = '1';";

    $result = mysqli_query($con, $sql);
    $students = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $students['year'];
}
function retrieve_second()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(yearLevel) as year from students where yearLevel = '2';";

    $result = mysqli_query($con, $sql);
    $students = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $students['year'];
}
function retrieve_third()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(yearLevel) as year from students where yearLevel = '3';";

    $result = mysqli_query($con, $sql);
    $students = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $students['year'];
}
function retrieve_fourth()
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT count(yearLevel) as year from students where yearLevel = '4';";

    $result = mysqli_query($con, $sql);
    $students = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $students['year'];
}

//Current Sit in Retrieval 
//Ugghhhh Redundant nasaddddd
function retrieve_c_programming_current()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');  

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'C-Programming' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}
function retrieve_c_sharp_programming_current()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');  

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'C# Programming' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}
function retrieve_java_programming_current()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');  

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'Java Programming' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}
function retrieve_asp_programming_current()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');  

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'ASP.Net Programming' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}
function retrieve_php_programming_current()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');  

    $sql = "SELECT count(sit_purpose) as lang FROM student_sit_in WHERE sit_purpose = 'Php Programming' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lang'];
}

//Laboratory
function retrieve_lab_524()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');

    $sql = "SELECT count(sit_lab) as lab FROM student_sit_in WHERE sit_lab = '524' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lab'];
}
function retrieve_lab_526()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');

    $sql = "SELECT count(sit_lab) as lab FROM student_sit_in WHERE sit_lab = '526' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lab'];
}
function retrieve_lab_528()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');

    $sql = "SELECT count(sit_lab) as lab FROM student_sit_in WHERE sit_lab = '528' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lab'];
}
function retrieve_lab_530()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');

    $sql = "SELECT count(sit_lab) as lab FROM student_sit_in WHERE sit_lab = '530' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lab'];
}
function retrieve_lab_542()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');

    $sql = "SELECT count(sit_lab) as lab FROM student_sit_in WHERE sit_lab = '542' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lab'];
}
function retrieve_lab_Mac()
{
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-m-d');

    $sql = "SELECT count(sit_lab) as lab FROM student_sit_in WHERE sit_lab = 'Mac' AND sit_date = '$date'";

    $result = mysqli_query($con, $sql);
    $language = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $language['lab'];
}

function retrieve_reservation(){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $listPerson = []; // Initialize listPerson as empty array before the query

    $sql = "SELECT * FROM reservation WHERE `status` = 'Pending' ORDER BY reservation_id desc ";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $listPerson[] = $row;
        }
    }
    return $listPerson; // Always return the array
}
function retrieve_reservation_logs(){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $listPerson = []; // Initialize listPerson as empty array before the query

    $sql = "SELECT * FROM reservation ORDER BY reservation_id desc ";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $listPerson[] = $row;
        }
    }
    return $listPerson; // Always return the array
}
function approve_reservation($reservation_id, $pc_number, $lab, $id_number){
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date("Y-m-d");

    // Validate PC number
    if (!is_numeric($pc_number) || $pc_number <= 0) {
        error_log("Error: Invalid PC number ($pc_number) for reservation ID $reservation_id");
        return false;
    }

    // Update reservation status
    $sql = "UPDATE `reservation` SET `status` = 'Approve' WHERE reservation_id = '$reservation_id'";
    
    // Log the lab parameter for debugging
    error_log("Lab parameter received: " . $lab);
    
    // Ensure the lab parameter has the correct format (should be lab_XXX)
    // If it doesn't start with "lab_", add the prefix
    if (strpos($lab, 'lab_') !== 0) {
        $lab = 'lab_' . $lab;
        error_log("Added prefix to lab parameter, now: " . $lab);
    }
    
    // Update PC status - making it unavailable (0 = used/unavailable, 1 = available)
    $sql_pc = "UPDATE `student_pc` SET `$lab` = '0' WHERE pc_id = '$pc_number'";

    // Log the queries for debugging
    error_log("Executing SQL for approval: " . $sql);
    error_log("Executing SQL for PC status update: " . $sql_pc);

    // Debug: check if PC exists before updating
    $check_pc_sql = "SELECT * FROM `student_pc` WHERE pc_id = '$pc_number'";
    $check_result = mysqli_query($con, $check_pc_sql);
    if(mysqli_num_rows($check_result) == 0) {
        error_log("Error: PC #$pc_number does not exist in the student_pc table!");
        
        // Insert PC if it doesn't exist
        $insert_pc_sql = "INSERT INTO `student_pc` (`pc_id`, `lab_517`, `lab_524`, `lab_526`, `lab_528`, `lab_530`, `lab_542`, `lab_Mac`) 
                          VALUES ('$pc_number', '1', '1', '1', '1', '1', '1', '1')";
        if(mysqli_query($con, $insert_pc_sql)) {
            error_log("Created missing PC #$pc_number in the database with default available status");
        } else {
            error_log("Failed to create PC #$pc_number: " . mysqli_error($con));
            return false;
        }
    }

    // Execute queries
    $result1 = mysqli_query($con, $sql);
    $result2 = mysqli_query($con, $sql_pc);

    if($result1 && $result2){
        notification($id_number,"Your Reservation has been approved! PC #".$pc_number." is now ready for your use. ".$date);
        error_log("Reservation approved successfully and PC status updated to 'Used'");
        return true;
    } else {
        error_log("Error in approve_reservation: " . mysqli_error($con));
        return false;
    }
}
function decline_reservation($reservation_id, $pc_number,$lab,$id_number){
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date("Y-m-d");

    $sql = "UPDATE `reservation` SET `status` = 'Decline' WHERE reservation_id = '$reservation_id'";
 

    if(mysqli_query($con,$sql) ){
        notification($id_number,"Your Reservation has been Denied! ".$date);
        return true;}
    else{return false;}
}
function notification($id_number,$message){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "INSERT INTO notification (`id_number`,`message`) VALUES ('$id_number','$message')";
    mysqli_query($con,$sql);
    
}

function reset_all_student_sessions($defaultValue = 30) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    try {
        // Ensure the default value doesn't exceed 30
        $defaultValue = min(30, (int)$defaultValue);
        
        // Update all students' session values to the default
        // IMPORTANT: Update the student_session table, not the students table
        $sql = "UPDATE student_session SET session = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $defaultValue);
        $result = $stmt->execute();
        
        // Close statement
        $stmt->close();
        
        return $result;
    } catch (Exception $e) {
        // Log error for debugging
        error_log("Reset sessions error: " . $e->getMessage());
        return false;
    }
}

// SCHEDULE MANAGEMENT FUNCTIONS
function add_schedule($title, $description, $start_date, $end_date, $start_time, $end_time, $lab, $resource, $posted_by) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "INSERT INTO schedules (title, description, start_date, end_date, start_time, end_time, lab, resource, posted_by, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssssss", $title, $description, $start_date, $end_date, $start_time, $end_time, $lab, $resource, $posted_by);
    
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error adding schedule: " . $stmt->error);
        return false;
    }
}

function get_all_schedules() {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "SELECT * FROM schedules ORDER BY start_date DESC, start_time ASC";
    $result = $con->query($sql);
    
    if (!$result) {
        error_log("Error retrieving schedules: " . $con->error);
        return [];
    }
    
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    
    return $schedules;
}

function get_schedule($id) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "SELECT * FROM schedules WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

function update_schedule($id, $title, $description, $start_date, $end_date, $start_time, $end_time, $lab, $resource) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "UPDATE schedules 
            SET title = ?, description = ?, start_date = ?, end_date = ?, 
                start_time = ?, end_time = ?, lab = ?, resource = ?, updated_at = NOW() 
            WHERE id = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssssi", $title, $description, $start_date, $end_date, $start_time, $end_time, $lab, $resource, $id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error updating schedule: " . $stmt->error);
        return false;
    }
}

function delete_schedule($id) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "DELETE FROM schedules WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error deleting schedule: " . $stmt->error);
        return false;
    }
}

function get_upcoming_schedules($limit = 5) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $today = date('Y-m-d');
    
    $sql = "SELECT * FROM schedules 
            WHERE end_date >= ? 
            ORDER BY start_date ASC, start_time ASC 
            LIMIT ?";
            
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $today, $limit);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
    
    return $schedules;
}

// RESOURCE MANAGEMENT FUNCTIONS
function add_resource($title, $description, $file_path, $file_type, $category, $posted_by) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "INSERT INTO resources (title, description, file_path, file_type, category, posted_by, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssss", $title, $description, $file_path, $file_type, $category, $posted_by);
    
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error adding resource: " . $stmt->error);
        return false;
    }
}

function get_all_resources() {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "SELECT * FROM resources ORDER BY created_at DESC";
    $result = $con->query($sql);
    
    if (!$result) {
        error_log("Error retrieving resources: " . $con->error);
        return [];
    }
    
    $resources = [];
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }
    
    return $resources;
}

function get_resources_by_category($category) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "SELECT * FROM resources WHERE category = ? ORDER BY created_at DESC";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $resources = [];
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }
    
    return $resources;
}

function get_resource($id) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "SELECT * FROM resources WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

function update_resource($id, $title, $description, $category) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "UPDATE resources 
            SET title = ?, description = ?, category = ?, updated_at = NOW() 
            WHERE id = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssi", $title, $description, $category, $id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Error updating resource: " . $stmt->error);
        return false;
    }
}

function delete_resource($id) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    // First get the file path to delete the actual file
    $sql = "SELECT file_path FROM resources WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $resource = $result->fetch_assoc();
        $file_path = $resource['file_path'];
        
        // Delete from database
        $sql = "DELETE FROM resources WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // If successful, try to delete the file
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
            return true;
        } else {
            error_log("Error deleting resource from database: " . $stmt->error);
            return false;
        }
    }
    
    return false;
}

function get_latest_resources($limit = 5) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $sql = "SELECT * FROM resources ORDER BY created_at DESC LIMIT ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $resources = [];
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }
    
    return $resources;
}

// Points System Functions
function get_current_semester() {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    try {
        $sql = "SELECT semester, academic_year FROM current_semester ORDER BY id DESC LIMIT 1";
        $result = $con->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return [
                'semester' => $row['semester'],
                'academic_year' => $row['academic_year']
            ];
        } else {
            // Return default values if no record is found
            return [
                'semester' => 'First Semester',
                'academic_year' => date('Y') . '-' . (date('Y') + 1)
            ];
        }
    } catch (Exception $e) {
        error_log("Error in get_current_semester: " . $e->getMessage());
        return [
            'semester' => 'First Semester',
            'academic_year' => date('Y') . '-' . (date('Y') + 1)
        ];
    }
}

function get_student_points($id_number) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $current_semester = get_current_semester();
    
    $sql = "SELECT points FROM student_points 
            WHERE id_number = ? 
            AND semester = ? 
            AND academic_year = ?";
            
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iss", $id_number, $current_semester['semester'], $current_semester['academic_year']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        // Initialize points if not exists
        $sql = "INSERT INTO student_points (id_number, points, semester, academic_year) 
                VALUES (?, 0, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iss", $id_number, $current_semester['semester'], $current_semester['academic_year']);
        $stmt->execute();
        
        return ['points' => 0];
    }
}

function get_leaderboard() {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    try {
        // Check if points column exists in students table
        $checkPointsColumn = $con->query("SHOW COLUMNS FROM students LIKE 'points'");
        if ($checkPointsColumn->num_rows == 0) {
            // Add points column if it doesn't exist
            $con->query("ALTER TABLE students ADD COLUMN points INT DEFAULT 0");
        }
        
        // Get the leaderboard directly from students table
        $sql = "SELECT s.id_number, 
                       CONCAT(s.firstName, ' ', s.middleName, ' ', s.lastName) as name, 
                       s.course, 
                       COALESCE(s.points, 0) as points
                FROM students s
                WHERE s.status = 'TRUE'
                ORDER BY s.points DESC, s.id_number";
                
        $result = $con->query($sql);
        
        if (!$result) {
            throw new Exception("Error retrieving leaderboard: " . $con->error);
        }
        
        $leaderboard = [];
        while ($row = $result->fetch_assoc()) {
            $leaderboard[] = $row;
        }
        
        return $leaderboard;
        
    } catch (Exception $e) {
        error_log("Error in get_leaderboard: " . $e->getMessage());
        return [];
    }
}

function update_student_points($id_number, $points) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $current_semester = get_current_semester();
    
    $sql = "UPDATE student_points 
            SET points = ? 
            WHERE id_number = ? 
            AND semester = ? 
            AND academic_year = ?";
            
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iiss", $points, $id_number, $current_semester['semester'], $current_semester['academic_year']);
    
    return $stmt->execute();
}

function reset_student_points($id_number) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    $current_semester = get_current_semester();
    
    $sql = "UPDATE student_points 
            SET points = 0 
            WHERE id_number = ? 
            AND semester = ? 
            AND academic_year = ?";
            
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iss", $id_number, $current_semester['semester'], $current_semester['academic_year']);
    
    return $stmt->execute();
}

function verify_database_structure() {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    // Create logs directory if it doesn't exist
    $logDir = dirname(__FILE__) . '/../logs';
    if (!file_exists($logDir)) {
        if (!mkdir($logDir, 0777, true)) {
            error_log("Failed to create logs directory at: " . $logDir);
            return false;
        }
        error_log("Created logs directory at: " . $logDir);
    }
    
    $logFile = $logDir . '/database_setup.log';
    
    function writeLog($message) {
        global $logFile;
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message\n";
        if (file_put_contents($logFile, $logMessage, FILE_APPEND) === false) {
            error_log("Failed to write to log file: " . $logFile);
            error_log("Log message: " . $message);
        }
    }
    
    try {
        writeLog("Starting database structure verification");
        
        // Check if points column exists in students table
        $checkPointsColumn = $con->query("SHOW COLUMNS FROM students LIKE 'points'");
        if ($checkPointsColumn->num_rows == 0) {
            writeLog("Points column not found in students table. Adding it now...");
            if (!$con->query("ALTER TABLE students ADD COLUMN points INT DEFAULT 0")) {
                throw new Exception("Failed to add points column: " . $con->error);
            }
            writeLog("Successfully added points column to students table");
        } else {
            writeLog("Points column already exists in students table");
        }
        
        // Check if points_history table exists
        $checkHistoryTable = $con->query("SHOW TABLES LIKE 'points_history'");
        if ($checkHistoryTable->num_rows == 0) {
            writeLog("points_history table not found. Creating it now...");
            $createHistoryTable = "CREATE TABLE points_history (
                id INT AUTO_INCREMENT PRIMARY KEY,
                student_id VARCHAR(50) NOT NULL,
                points_amount INT NOT NULL,
                transaction_type ENUM('add', 'reset', 'convert') NOT NULL,
                description TEXT,
                created_at DATETIME NOT NULL,
                INDEX (student_id)
            )";
            if (!$con->query($createHistoryTable)) {
                throw new Exception("Failed to create points_history table: " . $con->error);
            }
            writeLog("Successfully created points_history table");
        } else {
            writeLog("points_history table already exists");
        }
        
        // Check if notification table exists
        $checkNotificationTable = $con->query("SHOW TABLES LIKE 'notification'");
        if ($checkNotificationTable->num_rows == 0) {
            writeLog("notification table not found. Creating it now...");
            $createNotificationTable = "CREATE TABLE notification (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_number VARCHAR(50) NOT NULL,
                message TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX (id_number)
            )";
            if (!$con->query($createNotificationTable)) {
                throw new Exception("Failed to create notification table: " . $con->error);
            }
            writeLog("Successfully created notification table");
        } else {
            writeLog("notification table already exists");
        }
        
        writeLog("Database structure verification completed successfully");
        return true;
        
    } catch (Exception $e) {
        writeLog("Error verifying database structure: " . $e->getMessage());
        writeLog("SQL State: " . $con->sqlstate);
        writeLog("Error Code: " . $con->errno);
        error_log("Database structure verification failed: " . $e->getMessage());
        return false;
    }
}

function reset_all_points() {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    try {
        // Start transaction
        $con->begin_transaction();
        
        // First check if students table exists
        $checkStudentsTable = $con->query("SHOW TABLES LIKE 'students'");
        if ($checkStudentsTable->num_rows == 0) {
            throw new Exception("Students table does not exist");
        }
        
        // Check if status column exists in students table
        $checkStatusColumn = $con->query("SHOW COLUMNS FROM students LIKE 'status'");
        if ($checkStatusColumn->num_rows == 0) {
            throw new Exception("Status column does not exist in students table");
        }
        
        // Check if points column exists in students table
        $checkPointsColumn = $con->query("SHOW COLUMNS FROM students LIKE 'points'");
        if ($checkPointsColumn->num_rows == 0) {
            // Add points column if it doesn't exist
            if (!$con->query("ALTER TABLE students ADD COLUMN points INT DEFAULT 0")) {
                throw new Exception("Failed to add points column: " . $con->error);
            }
        }
        
        // Reset points in students table
        $sql = "UPDATE students SET points = 0 WHERE status = 'TRUE'";
        if (!$con->query($sql)) {
            throw new Exception("Error updating points: " . $con->error);
        }
        
        // Check if points_history table exists
        $checkHistoryTable = $con->query("SHOW TABLES LIKE 'points_history'");
        if ($checkHistoryTable->num_rows > 0) {
            // Record in points history
            $history_sql = "INSERT INTO points_history 
                           (student_id, points_amount, transaction_type, description, created_at) 
                           SELECT id_number, points, 'reset', 'Points reset by admin', NOW()
                           FROM students 
                           WHERE status = 'TRUE'";
            if (!$con->query($history_sql)) {
                throw new Exception("Error recording points history: " . $con->error);
            }
        }
        
        // Check if notification table exists
        $checkNotificationTable = $con->query("SHOW TABLES LIKE 'notification'");
        if ($checkNotificationTable->num_rows > 0) {
            // Add notification for all active students
            $notify_sql = "INSERT INTO notification (id_number, message) 
                          SELECT id_number, 'All points have been reset to 0.'
                          FROM students 
                          WHERE status = 'TRUE'";
            if (!$con->query($notify_sql)) {
                throw new Exception("Error adding notifications: " . $con->error);
            }
        }
        
        // Commit transaction
        $con->commit();
        return true;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        if ($con->ping()) {
            $con->rollback();
        }
        error_log("Points reset error: " . $e->getMessage());
        error_log("SQL State: " . $con->sqlstate);
        error_log("Error Code: " . $con->errno);
        return false;
    }
}

function add_student_points($id_number, $points_to_add) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    try {
        $con->begin_transaction();
        
        // Update points in students table
        $update_sql = "UPDATE students 
                      SET points = COALESCE(points, 0) + ? 
                      WHERE id_number = ?";
        $update_stmt = $con->prepare($update_sql);
        $update_stmt->bind_param("is", $points_to_add, $id_number);
        $update_stmt->execute();
        
        // Record in points history if the table exists
        $historyTableCheck = $con->query("SHOW TABLES LIKE 'points_history'");
        if ($historyTableCheck->num_rows > 0) {
            $history_sql = "INSERT INTO points_history 
                           (student_id, points_amount, transaction_type, description, created_at) 
                           VALUES (?, ?, 'add', 'Points added by admin', NOW())";
            $history_stmt = $con->prepare($history_sql);
            $history_stmt->bind_param("si", $id_number, $points_to_add);
            $history_stmt->execute();
        }
        
        // Add notification for student
        $notification_message = "You have been awarded {$points_to_add} points.";
        $notify_sql = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
        $notify_stmt = $con->prepare($notify_sql);
        $notify_stmt->bind_param("ss", $id_number, $notification_message);
        $notify_stmt->execute();
        
        $con->commit();
        return true;
        
    } catch (Exception $e) {
        $con->rollback();
        error_log("Error adding points: " . $e->getMessage());
        return false;
    }
}

function update_current_semester($semester, $academic_year) {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    try {
        $sql = "INSERT INTO current_semester (semester, academic_year) VALUES (?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $semester, $academic_year);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error updating current semester: " . $stmt->error);
            return false;
        }
    } catch (Exception $e) {
        error_log("Exception in update_current_semester: " . $e->getMessage());
        return false;
    }
}

?>

