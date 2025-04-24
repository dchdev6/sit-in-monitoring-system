<?php
    include '../backend/backend_index.php';

    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>

<?php

//Login
if(isset($_POST["submit"])){
    $idNum = $_POST["idNum"];
    $password = $_POST["password"];

    // Admin Login
    if(admin_login($idNum,$password)){
        $_SESSION['admin_name'] = 'CCS Admin';
        $_SESSION['admin_id_number'] = 1;
        $_SESSION["admin_id"] = 1;
        echo '<script>window.location.href = "../view/admin/admin.php";</script>';
    }
    else{
        $user = student_login($idNum,$password);
        
        if($user['id_number'] != null){
            
            $_SESSION['id_number'] = $user["id_number"];
            $_SESSION['name'] =  $user["firstName"]." ".$user["middleName"]." ".$user["lastName"];
            $_SESSION["lname"] = $user["lastName"];
            $_SESSION["fname"] = $user["firstName"];
            $_SESSION["mname"] = $user["middleName"];
            $_SESSION["yearLevel"] = $user["yearLevel"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["course"] = $user["course"];
            $_SESSION["address"] = $user["address"];
            $_SESSION['remaining'] = $user["session"];
            $_SESSION["id"] = 1;
            
            // Fix profile image handling - make sure we use the actual image or default if not set
            $_SESSION["profile_image"] = !empty($user["profile_image"]) ? $user["profile_image"] : "default-profile.jpg";
            error_log("Login successful - Profile image set to: " . $_SESSION["profile_image"]);
        
            echo '<script>window.location.href = "../view/student/homepage.php";</script>';	
        }
        else
        {
            echo '<script>Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Incorret ID Number and Password!",
                
              });</script>'; 
        }
    }
}

// If we reach this page from a register form submission, redirect back to register.php
// This is a fallback in case someone submits directly to this file
if(isset($_POST["submitRegister"])){
    echo '<script>
        Swal.fire({
            icon: "warning",
            title: "Processing Error",
            text: "There was an error processing your registration. Please try again.",
        }).then(() => {
            window.location.href = "../auth/register.php";
        });
    </script>';
}
?>