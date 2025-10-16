<?php
session_start();
include '../db.php';

// Security Check: Only allow logged-in admins
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    die("Access Denied.");
}

// Check if a user ID was provided in the URL
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // You should not be able to delete your own account while logged in
    if ($user_id == $_SESSION['user_id']) {
        header("Location: manage_users.php?error=cannotdelete");
        exit();
    }

    // Prepare a statement to delete the user from the users table
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Redirect back to the management page with a success message
        header("Location: manage_users.php?success=deleted");
        exit();
    } else {
        // Handle error
        header("Location: manage_users.php?error=deletefailed");
        exit();
    }
} else {
    // If no ID was provided, just go back
    header("Location: manage_users.php");
    exit();
}
?>