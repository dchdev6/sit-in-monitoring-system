<?php
include 'database_connection.php';

function upload_profile_image($file, $idNumber) {
    $db = Database::getInstance();
    $con = $db->getConnection();

    // ✅ Ensure correct folder path
    $targetDir = __DIR__ . "/../images/";  
    $fileName = basename($file["name"]); // Prevent duplicate filenames
    $targetFilePath = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // ✅ Validate Image File
    if (!getimagesize($file["tmp_name"])) {
        return "Error: Uploaded file is not an image.";
    }

    // ✅ Check file size (Max: 2MB)
    if ($file["size"] > 2097152) {
        return "Error: File size too large. Max 2MB.";
    }

    // ✅ Allowed file types
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedTypes)) {
        return "Error: Invalid file type.";
    }

    // ✅ Move file & update database
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        // ✅ Save only the filename in DB, not the full path
        $sql = "UPDATE students SET profile_image = ? WHERE id_number = ?";
        $stmt = $con->prepare($sql);
        if (!$stmt) {
            die("SQL Prepare Failed: " . $con->error);
        }
        $stmt->bind_param("ss", $fileName, $idNumber);

        if ($stmt->execute()) {
            $_SESSION["profile_image"] = $fileName; // ✅ Update session
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

    // SQL query to update student information
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

    // Prepare the SQL statement
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        die("Error preparing SQL: " . $con->error);
    }

    // Bind parameters to the SQL statement
    $stmt->bind_param("sssssssss", $last_Name, $first_Name, $middle_Name, $course_Level, $course, $email, $address, $profile_image, $idNum);

    // Execute the SQL statement
    if ($stmt->execute()) {
        error_log("Profile updated successfully for ID: " . $idNum);
        return true; // Update successful
    } else {
        error_log("Database update failed: " . $stmt->error);
        die("Database update failed: " . $stmt->error);
    }
}

function loginStudent()
{

    if ($_SESSION['id_number'] != 0 && !isset($_SESSION['success_toast_displayed'])) {
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
    } else if ($_SESSION['id_number'] == null) {
        echo '<script>window.location.href = "../../Login.php";</script>';
    }
}

function retrieve_student_history($idNumber){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sqlTable = " SELECT student_sit_in.sit_id, students.id_number, students.firstName,students.lastName,
    student_sit_in.sit_purpose, student_sit_in.sit_lab , student_sit_in.sit_login,
    student_sit_in.sit_logout,student_sit_in.sit_date, student_sit_in.status FROM
    students INNER JOIN student_sit_in ON students.id_number = student_sit_in.id_number
        INNER JOIN student_session ON student_sit_in.id_number = student_session.id_number WHERE student_sit_in.status = 'Finished' AND student_sit_in.id_number = '$idNumber';";

    $result = mysqli_query($con, $sqlTable);
    if (mysqli_num_rows($result) > 0) {
        $listPerson = [];
        while ($row = mysqli_fetch_array($result)) {
            $listPerson[] = $row;
        }
    }

    return $listPerson;
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

function submit_feedback($id,$lab,$message){
    $db = Database::getInstance();
    $con = $db->getConnection();
    $date = date('Y-M-d');

    $sql = "INSERT INTO feedback (`id_number`,`lab`,`date`,`message`)VALUES ('$id','$lab','$date','$message')";
    if(mysqli_query($con, $sql)){
      
        return true;
    }
    else{
        return false;
    }
}

function submit_reservation($id_number, $purpose, $lab, $pc_number, $time, $date)
{
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "INSERT INTO `reservation` (`reservation_date`,`reservation_time`,`pc_number`,`lab`,`purpose`,`id_number`,`status`) VALUES('$date','$time','$pc_number','$lab','$purpose','$id_number','Pending')";

    if (mysqli_query($con, $sql)) {
       
        return true;
    } else {
        // Log or display MySQL errors
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
        return false;
    }
}
function retrieve_reservation_logs($id_number){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM reservation WHERE id_number = '$id_number'  ORDER BY reservation_id desc ";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $listPerson = [];
        while ($row = mysqli_fetch_array($result)) {
            $listPerson[] = $row;
        }
    }
    return $listPerson;
}

function notifications($id_number,$message){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "INSERT INTO notification (`id_number`,`message`) VALUES ('$id_number','$message')";
    mysqli_query($con,$sql);
    
}
function retrieve_notification($id_number){
    $db = Database::getInstance();
    $con = $db->getConnection();

    $sql = "SELECT * FROM `notification` WHERE id_number = '$id_number' ORDER BY notification_id desc";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $notification = [];
        while ($row = mysqli_fetch_array($result)) {
            $notification[] = $row;
        }
    }
    return $notification;
}