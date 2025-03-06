<?php
session_start(); // Start session for authentication handling

// Redirect to login page
header("Location: ../../auth/login.php");
exit(); // Ensure script stops execution after redirection
?>
