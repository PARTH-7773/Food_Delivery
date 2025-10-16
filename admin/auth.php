<?php
session_start();
// If admin_id is not set in the session, redirect back to the login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
// session_destroy();

// Redirect to the homepage
// header("Location: dashboard.php");
// exit();

?>