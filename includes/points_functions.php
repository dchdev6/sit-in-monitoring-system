<?php
// Connect to database
require_once __DIR__ . '/../backend/database_connection.php';

/**
 * Get student points from database
 */
if (!function_exists('get_student_points')) {
    function get_student_points($student_id) {
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        try {
            $query = "SELECT IFNULL(points, 0) as points FROM students WHERE id_number = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                return $row['points'];
            }
            return 0;
        } catch (Exception $e) {
            error_log('Error getting student points: ' . $e->getMessage());
            return 0;
        }
    }
}

/**
 * Get leaderboard data
 */
if (!function_exists('get_leaderboard')) {
    function get_leaderboard($limit = 25) {
        $db = Database::getInstance();
        $con = $db->getConnection();
        $students = [];
        
        try {
            error_log("Executing leaderboard query with limit: $limit");
            
            // First, check the column names in the database
            $tableInfo = $con->query("DESCRIBE students");
            $columns = [];
            while ($col = $tableInfo->fetch_assoc()) {
                $columns[] = $col['Field'];
            }
            
            // Debug output
            error_log("Students table columns: " . implode(", ", $columns));
            
            // Determine the correct column names based on what's available
            $idField = in_array('id', $columns) ? 'id' : (in_array('student_id', $columns) ? 'student_id' : 'id_number');
            $idNumberField = in_array('id_number', $columns) ? 'id_number' : 'idNumber';
            $firstNameField = in_array('firstName', $columns) ? 'firstName' : (in_array('first_name', $columns) ? 'first_name' : 'fname');
            $lastNameField = in_array('lastName', $columns) ? 'lastName' : (in_array('last_name', $columns) ? 'last_name' : 'lname');
            $programField = in_array('course', $columns) ? 'course' : (in_array('program', $columns) ? 'program' : 'course');
            $emailField = in_array('email', $columns) ? 'email' : 'emailAddress';
            
            // Construct a query that works with the actual database schema
            $query = "SELECT 
                     $idField, 
                     $idNumberField as id_number, 
                     $firstNameField as first_name, 
                     $lastNameField as last_name, 
                     $programField as program, 
                     $emailField as email, 
                     points 
                     FROM students 
                     WHERE points > 0 
                     ORDER BY points DESC, $lastNameField ASC
                     LIMIT ?";
            
            error_log("Leaderboard query: $query");
            
            $stmt = $con->prepare($query);
            
            // Check if prepare was successful
            if ($stmt === false) {
                error_log("Failed to prepare leaderboard query: " . $con->error);
                return [];
            }
            
            // Bind the limit parameter
            if (!$stmt->bind_param("i", $limit)) {
                error_log("Failed to bind parameter: " . $stmt->error);
                return [];
            }
            
            // Execute the statement
            if (!$stmt->execute()) {
                error_log("Failed to execute leaderboard query: " . $stmt->error);
                return [];
            }
            
            $result = $stmt->get_result();
            
            // Fetch results
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
            
            error_log("Retrieved " . count($students) . " students for leaderboard");
            return $students;
            
        } catch (Exception $e) {
            error_log('Error getting leaderboard: ' . $e->getMessage());
            return [];
        }
    }
}

/**
 * Get points history for a student
 */
if (!function_exists('get_points_history')) {
    function get_points_history($student_id) {
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        try {
            $query = "SELECT 
                        ph.id, 
                        ph.student_id, 
                        ph.points_amount as points, 
                        ph.description as reason, 
                        ph.transaction_type,
                        ph.created_at 
                      FROM 
                        points_history ph
                      WHERE 
                        ph.student_id = ?
                      ORDER BY 
                        ph.created_at DESC";
                        
            $stmt = $con->prepare($query);
            
            if (!$stmt) {
                error_log("Failed to prepare points history query: " . $con->error);
                return [];
            }
            
            $stmt->bind_param("s", $student_id);
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
 * Award points to a student
 */
if (!function_exists('award_points_to_student')) {
    function award_points_to_student($student_id, $points, $reason) {
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        try {
            // Start transaction
            $con->begin_transaction();
            
            // Update student points
            $update_query = "UPDATE students SET points = points + ? WHERE id_number = ?";
            $update_stmt = $con->prepare($update_query);
            
            if (!$update_stmt) {
                error_log("Failed to prepare update statement: " . $con->error);
                return false;
            }
            
            $update_stmt->bind_param("is", $points, $student_id);
            $update_result = $update_stmt->execute();
            
            if (!$update_result) {
                $con->rollback();
                error_log("Failed to update student points: " . $update_stmt->error);
                return false;
            }
            
            // Add to points history
            $history_query = "INSERT INTO points_history 
                             (student_id, points_amount, transaction_type, description, created_at) 
                             VALUES (?, ?, 'add', ?, NOW())";
                             
            $history_stmt = $con->prepare($history_query);
            
            if (!$history_stmt) {
                $con->rollback();
                error_log("Failed to prepare history statement: " . $con->error);
                return false;
            }
            
            $history_stmt->bind_param("sis", $student_id, $points, $reason);
            $history_result = $history_stmt->execute();
            
            if (!$history_result) {
                $con->rollback();
                error_log("Failed to record points history: " . $history_stmt->error);
                return false;
            }
            
            // Everything successful, commit transaction
            $con->commit();
            
            // Create notification for the student
            $notification_message = "You have been awarded {$points} points. Reason: {$reason}";
            
            // Add notification
            $notify_query = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
            $notify_stmt = $con->prepare($notify_query);
            
            if ($notify_stmt) {
                $notify_stmt->bind_param("ss", $student_id, $notification_message);
                $notify_stmt->execute();
            }
            
            return true;
            
        } catch (Exception $e) {
            // If anything fails, roll back the transaction
            $con->rollback();
            error_log('Error awarding points: ' . $e->getMessage());
            return false;
        }
    }
}

/**
 * Use points for reservation
 */
if (!function_exists('use_points_for_reservation')) {
    function use_points_for_reservation($student_id) {
        $db = Database::getInstance();
        $con = $db->getConnection();
        $points_needed = 3; // Cost of reservation
        
        try {
            // Start transaction
            $con->begin_transaction();
            
            // Check if student has enough points
            $points_check_query = "SELECT points FROM students WHERE id_number = ? AND points >= ?";
            $check_stmt = $con->prepare($points_check_query);
            $check_stmt->bind_param("si", $student_id, $points_needed);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows == 0) {
                return false; // Not enough points
            }
            
            // Deduct points
            $update_query = "UPDATE students SET points = points - ? WHERE id_number = ?";
            $update_stmt = $con->prepare($update_query);
            $update_stmt->bind_param("is", $points_needed, $student_id);
            $update_result = $update_stmt->execute();
            
            if (!$update_result) {
                $con->rollback();
                return false;
            }
            
            // Record the transaction
            $history_query = "INSERT INTO points_history 
                             (student_id, points_amount, transaction_type, description, created_at) 
                             VALUES (?, ?, 'deduct', 'Used for sit-in reservation', NOW())";
            $history_stmt = $con->prepare($history_query);
            $history_stmt->bind_param("si", $student_id, $points_needed);
            $history_result = $history_stmt->execute();
            
            if (!$history_result) {
                $con->rollback();
                return false;
            }
            
            // Commit transaction
            $con->commit();
            
            return true;
            
        } catch (Exception $e) {
            $con->rollback();
            error_log('Error using points for reservation: ' . $e->getMessage());
            return false;
        }
    }
}

/**
 * Get pending point requests
 */
if (!function_exists('get_pending_point_requests')) {
    function get_pending_point_requests() {
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        try {
            $query = "SELECT pr.*, s.firstName, s.lastName, s.id_number 
                      FROM points_requests pr
                      JOIN students s ON pr.student_id = s.id_number
                      WHERE pr.status = 'pending'
                      ORDER BY pr.request_date DESC";
                      
            $result = $con->query($query);
            
            if (!$result) {
                error_log("Error retrieving pending point requests: " . $con->error);
                return [];
            }
            
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
}

/**
 * Process a point request (approve or reject)
 */
if (!function_exists('process_points_request')) {
    function process_points_request($request_id, $status) {
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        try {
            // Start transaction
            $con->begin_transaction();
            
            // Get the request details
            $get_request_query = "SELECT * FROM points_requests WHERE id = ?";
            $get_request_stmt = $con->prepare($get_request_query);
            $get_request_stmt->bind_param("i", $request_id);
            $get_request_stmt->execute();
            $request_result = $get_request_stmt->get_result();
            
            if ($request_result->num_rows === 0) {
                $con->rollback();
                error_log("Request ID $request_id not found");
                return false;
            }
            
            $request = $request_result->fetch_assoc();
            $student_id = $request['student_id'];
            $points_amount = $request['points_amount'];
            $request_type = $request['request_type'];
            
            // Update request status
            $update_request_query = "UPDATE points_requests SET status = ?, processed_date = NOW() WHERE id = ?";
            $update_request_stmt = $con->prepare($update_request_query);
            $update_request_stmt->bind_param("si", $status, $request_id);
            $update_request_result = $update_request_stmt->execute();
            
            if (!$update_request_result) {
                $con->rollback();
                error_log("Failed to update request status: " . $update_request_stmt->error);
                return false;
            }
            
            // If approved, update student points and add to history
            if ($status === 'approved') {
                // Update student points
                $update_points_query = "UPDATE students SET points = points + ? WHERE id_number = ?";
                $update_points_stmt = $con->prepare($update_points_query);
                $update_points_stmt->bind_param("is", $points_amount, $student_id);
                $update_points_result = $update_points_stmt->execute();
                
                if (!$update_points_result) {
                    $con->rollback();
                    error_log("Failed to update student points: " . $update_points_stmt->error);
                    return false;
                }
                
                // Add to history
                $description = ucfirst($request_type) . " points request approved";
                
                $history_query = "INSERT INTO points_history 
                                 (student_id, points_amount, transaction_type, description, request_id, created_at) 
                                 VALUES (?, ?, 'add', ?, ?, NOW())";
                                 
                $history_stmt = $con->prepare($history_query);
                $history_stmt->bind_param("sisi", $student_id, $points_amount, $description, $request_id);
                $history_result = $history_stmt->execute();
                
                if (!$history_result) {
                    $con->rollback();
                    error_log("Failed to add points history: " . $history_stmt->error);
                    return false;
                }
                
                // Create notification for student
                $notification_message = "Your {$request_type} points request for {$points_amount} points has been approved!";
            } else {
                // Create rejection notification
                $notification_message = "Your {$request_type} points request for {$points_amount} points has been rejected.";
            }
            
            // Add notification
            $notify_query = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
            $notify_stmt = $con->prepare($notify_query);
            
            if ($notify_stmt) {
                $notify_stmt->bind_param("ss", $student_id, $notification_message);
                $notify_stmt->execute();
            }
            
            // Commit transaction
            $con->commit();
            
            return true;
            
        } catch (Exception $e) {
            $con->rollback();
            error_log('Error processing point request: ' . $e->getMessage());
            return false;
        }
    }
}
?>