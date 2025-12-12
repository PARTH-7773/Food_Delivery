<?php
include 'auth.php'; // Secure this page
include '../db.php'; // Connect to the database

// Fetch statistics from the database
$total_orders_result = $conn->query("SELECT COUNT(id) as total FROM orders");
$total_orders = $total_orders_result->fetch_assoc()['total'];

$total_revenue_result = $conn->query("SELECT SUM(total_amount) as total FROM orders");
$total_revenue = $total_revenue_result->fetch_assoc()['total'];

$total_users_result = $conn->query("SELECT COUNT(id) as total FROM users WHERE role = 'user'");
$total_users = $total_users_result->fetch_assoc()['total'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
    .act {
        margin-left: .001rem;
    }
</style>

<body class="admin-body">


    <aside class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="manage_orders.php"><i class="fas fa-box"></i> Manage Orders</a></li>
            <li><a href="manage_products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_products.php' ? 'active' : ''; ?>"><i class="fas fa-utensils"></i> Manage Products</a></li>
            <li><a href="../" target="_blank"><i class="fas fa-globe"></i> View Main Site</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main-content">
        <header>
            <nav class="navbar flex between wrapper">
                <a href="index.html" class="logo">Food Delivery</a>
                <div>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name'] . " "); ?>!</span>
                    <a href="../logout.php" class="btn act">Logout <i class="fas fa-sign-out-alt"></i></a>
                </div>
            </nav>
        </header>
        <nav class="navbar flex between wrapper">
            <a href="dashboard.php" class="logo"></a>
        </nav>

        <header class="flex between">
            <h1>Dashboard</h1>
        </header>

        <div class="stat-cards" style="margin-top: 2rem;">
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p>₹<?php echo number_format($total_revenue ?? 0, 2); ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <p><?php echo $total_orders ?? 0; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Customers</h3>
                <p><?php echo $total_users ?? 0; ?></p>
            </div>
        </div>

    </main>

</body>

</html>