<?php
// signup.php

session_start();
include 'db.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error_message = "An account with this email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt_insert->execute()) {
                // --- CHANGE IS HERE ---
                // Instead of redirecting, we set a success message.
                $success_message = "Account created successfully! You can now log in.";
            } else {
                $error_message = "Error: Could not register. Please try again.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Foody Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="navbar flex between wrapper">
            <a href="index.php" class="logo">Food Delivery</a>
        </nav>
    </header>
    <main class="signin-container">
        <div class="form-wrapper">
            
            <?php if (empty($success_message)): ?>
                <h2>Create an Account</h2>

                <?php if (!empty($error_message)): ?>
                    <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                
                <form action="signup.php" method="POST">
                    <div class="input-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                    <div class="input-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                    <button type="submit" class="btn">Sign Up</button>
                </form>
                <p class="form-footer">
                    Already have an account? <a href="signin.php">Sign In</a>
                </p>

            <?php else: ?>
                <div style="text-align: center;">
                    <h2>Account Created! ✅</h2>
                    <p><?php echo $success_message; ?></p>
                    <br>
                    <a href="signin.php" class="btn">Go to Sign In</a>
                </div>
            <?php endif; ?>

        </div>
    </main>
</body>
</html>