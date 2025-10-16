<?php
session_start();
// CORRECTED PATH: 'db.php' is in the same folder
include 'db.php'; 

if (isset($_POST['login_submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        // CORRECTED PATH: Redirect to login.php in the same folder
        header("Location: signin.php?error=emptyfields");
        exit();
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // CORRECTED PATH: Redirect to dashboard.php in the same folder
            // header("Location: dashboard.php");
            exit();
        } else {
            // CORRECTED PATH: Redirect to login.php in the same folder
            header("Location: login.php?error=invalidcred");
            exit();
        }
    } else {
        // CORRECTED PATH: Redirect to login.php in the same folder
        header("Location: login.php?error=invalidcred");
        exit();
    }
} else {
    // CORRECTED PATH: Redirect to index.php in the same folder
    header("Location: index.html");
    exit();
}
?>