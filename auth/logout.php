<?php
    session_start();
    session_unset(); // Clears session variables
    session_destroy(); // Ends session
    
    header("Location: ../auth/login.php");
    exit();
    
?>