<?php
include 'auth.php';
include '../db.php';
$users_result = $conn->query("SELECT id, name, email, role FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .data-table th,
        .data-table td {
            border-bottom: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }

        .data-table th {
            background-color: var(--hint-yellow);
        }
        .action-link-delete{
            color: red;
            padding: 1rem;
        }
        .action-link-delete:hover{
            text-decoration: underline;
        }
    </style>
</head>

<body class="admin-body">

    <aside class="admin-sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_users.php" class="active"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="manage_orders.php"><i class="fas fa-box"></i> Manage Orders</a></li>
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
        <header class="flex between">
            <h1>Manage Users</h1>
            
        </header></nav>

        <table class="data-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users_result->num_rows > 0): ?>
                    <?php while ($user = $users_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="action-link-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>

</html>
<?php $conn->close(); ?>