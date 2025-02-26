<?php

include __DIR__. '../../backend/backend_student.php';

loginStudent();

session_start(); // ✅ Ensure session starts

if (isset($_POST["submit"])) {
    $idNum = $_POST['idNumber'];
    $last_Name = $_POST['lName'];
    $first_Name = $_POST['fName'];
    $middle_Name = $_POST['mName'];
    $course_Level = $_POST['courseLevel'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $address = $_POST['address'];

    // ✅ Handle Profile Image Upload
    if (!empty($_FILES["profile_image"]["name"])) {
        $uploadStatus = upload_profile_image($_FILES["profile_image"], $idNum);
        if ($uploadStatus !== "Success") {
            die("Upload Error: " . $uploadStatus);
        }
    }

    // ✅ Update Profile
    $updateResult = edit_student_student($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $email, $course, $address, $_SESSION["profile_image"]);
    if ($updateResult) {
        session_write_close(); // ✅ Force session update
        header("Location: ../view/student/homepage.php");
        exit;
    } else {
        die("Profile Update Failed");
    }
}



// ✅ Handle Feedback Submission
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

// ✅ Handle Reservation Submission
if (isset($_POST['reserve_user'])) {
    $id_number = $_POST['id_number'];
    $purpose = $_POST['purpose'];
    $lab = $_POST['lab'];
    $pc_number = $_POST['pc_number'];
    $time = $_POST['time'];
    $date = $_POST['date'];

    if (submit_reservation($id_number, $purpose, $lab, $pc_number, $time, $date)) {
        echo "<script>Swal.fire({title: 'Success', text: 'Reservation Submitted', icon: 'success', timer: 2000});</script>";
        notifications($id_number, "Reservation Confirmed! | $date\nYou have successfully submitted a reservation.");
    }
}
?>
