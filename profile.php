<?php
session_start();
include 'db.php';

// 1. Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// 2. Handle Form Submission (Update Data)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $phone, $address, $user_id);

    if ($stmt->execute()) {
        $msg = "Profile Updated Successfully!";
        $_SESSION['user_name'] = $name; // Update session name too
    } else {
        $msg = "Error updating profile.";
    }
}

// 3. Fetch Current Data to Show in Form
$stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Profile - Food Delivery</title>
    <link rel="stylesheet" href="my_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <header>
        <nav class="navbar flex between wrapper">
            <a href="index.php" class="logo">Food Delivery</a>
            <div class="desktop-action flex gap-2">
                <a href="index.php" class="btn">Home</a>
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </nav>
    </header>

    <main class="signin-container">
        <div class="form-wrapper">
            <h2 style="margin-bottom: 1rem;">My Profile</h2>

            <?php if ($msg) echo "<p style='color:green; text-align:center; margin-bottom:1rem;'>$msg</p>"; ?>

            <form method="POST">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Email (Cannot be changed)</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background:#f0f0f0; color:#888;">
                </div>

                <div class="input-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Enter your phone number">
                </div>

                <div class="input-group">
                    <label>Delivery Address</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" placeholder="Enter your full address">
                </div>

                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
    </main>

</body>

</html>