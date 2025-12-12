<?php
session_start();
include 'db.php';

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Fetch orders for this specific user
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
// Note: If you haven't added user_id to orders table yet, we might need to fix that first.
// For now, let's assume we are matching by customer_name or just showing all for demo (better to add user_id column).
// To make it simple for now without changing DB structure too much, let's fetch by Phone Number or Email if you stored it.
// Ideally, we should update the 'place_order.php' to save 'user_id'. 

// Let's do the PROPER fix first.
// We will fetch by the user's name for now since we saved that.
$user_name = $_SESSION['user_name'];
// Updated Query to use User ID
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders - Food Delivery</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .orders-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .order-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .order-status {
            padding: 5px 10px;
            border-radius: 20px;
            background: #e0f2fe;
            color: #0284c7;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .order-items ul {
            list-style: none;
            padding: 0;
        }

        .order-items li {
            color: #555;
            margin-bottom: 5px;
        }

        .total-price {
            color: var(--gold-finger);
            font-size: 1.2rem;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>

    <header>
        <nav class="navbar flex between wrapper">
            <a href="index.php" class="logo">Food Delivery</a>
            <div class="desktop-action flex gap-2">
                <span style="align-self: center;">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="index.php" class="btn">Home</a>
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </nav>
    </header>

    <main class="wrapper p-top">
        <div class="orders-container">
            <h2 style="margin-bottom: 2rem;">My Order History</h2>

            <?php if ($result->num_rows > 0): ?>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <strong>Order #<?php echo $order['id']; ?></strong><br>
                                <small style="color: gray;"><?php echo $order['order_date']; ?></small>
                            </div>
                            <div>
                                <span class="order-status">Received</span>
                            </div>
                        </div>

                        <div class="order-items">
                            <ul>
                                <?php
                                // Fetch items for this order
                                $oid = $order['id'];
                                $item_sql = "SELECT p.name, oi.quantity, oi.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $oid";
                                $item_res = $conn->query($item_sql);
                                while ($item = $item_res->fetch_assoc()):
                                ?>
                                    <li>
                                        <?php echo $item['quantity']; ?>x <?php echo $item['name']; ?>
                                        <span style="float:right;">₹<?php echo $item['price']; ?></span>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>

                        <hr style="border: 0; border-top: 1px dashed #ddd; margin: 10px 0;">
                        <div class="total-price">
                            Total: ₹<?php echo number_format($order['total_amount'], 2); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center">
                    <h3>No orders found.</h3>
                    <p>Go ahead and order some delicious food!</p>
                    <br>
                    <a href="index.php" class="btn">View Menu</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>

</html>