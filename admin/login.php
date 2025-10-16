<?php
session_start();
include '../db.php'; // Note the .. to go up one directory to find db.php
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check for an admin user specifically
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $name;
            header("Location: dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            $error_message = "Invalid credentials for admin.";
        }
    } else {
        $error_message = "Admin account not found.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../style.css"> </head>
<body>
    <header>
        <nav class="navbar flex between wrapper">
            <a href="index.html" class="logo">Food Delivery</a>
        </nav>
    </header>
    <main class="signin-container">
        <div class="form-wrapper">
            <h2>Admin Login</h2>
            <?php if ($error_message): ?>
                <p style="color:red; text-align:center;"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </main>
</body>
</html>