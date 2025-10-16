<?php
include 'auth.php';
include '../db.php';
$orders_result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
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
            vertical-align: top;
        }

        .data-table th {
            background-color: var(--hint-yellow);
        }

        .order-items-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
    </style>
</head>

<body class="admin-body">

    <aside class="admin-sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="manage_orders.php" class="active"><i class="fas fa-box"></i> Manage Orders</a></li>
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
                <h1>Manage Orders</h1>
            </header>
        </nav>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Details</th>
                        <th>Items Ordered</th>
                        <th>Total (₹)</th>
                        <th>Address</th>
                        <th>Phone NO</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders_result->num_rows > 0): ?>
                        <?php while ($order = $orders_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>

                                </td>
                                <td>
                                    <ul class="order-items-list">
                                        <?php
                                        $order_id = $order['id'];
                                        $items_sql = "SELECT p.name, oi.quantity FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id";
                                        $items_result = $conn->query($items_sql);
                                        if ($items_result && $items_result->num_rows > 0) {
                                            while ($item = $items_result->fetch_assoc()) {
                                                echo '<li>' . htmlspecialchars($item['name']) . ' (x' . $item['quantity'] . ')</li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </td>
                                <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_address']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_phone']); ?></td>
                                <td><?php echo $order['order_date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
    </main>
</body>

</html>
<?php $conn->close(); ?>