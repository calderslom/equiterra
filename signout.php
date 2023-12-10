<?php
//starting the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

// Unset all of the session variables
session_unset();

// Destroy the session
session_destroy();

//unset user type
unset($_SESSION['user_type']);

// Redirect to the login page
header('Location: index.php');
exit;
?>