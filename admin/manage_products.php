<?php
include 'auth.php';
include '../db.php';

// --- 1. HANDLE ADD PRODUCT ---
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Image Upload Logic
    $target_dir = "../uploads/"; // Save in the uploads folder outside admin
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    $db_image_path = "uploads/" . $image_name; // Path to save in DB

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Save to Database (Price is stored in USD in DB based on previous setup, or convert here. 
        // Let's assume you enter the INR price and we divide by 80 to store as USD, 
        // OR if you changed logic to store INR directly, just save $price.
        // *For this code, we assume you enter the Dollar amount to match existing logic*
        // If you want to enter Rupees, change logic: $price = $price / 80;

        $stmt = $conn->prepare("INSERT INTO products (name, price, category, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $category, $db_image_path);

        if ($stmt->execute()) {
            $msg = "Product Added Successfully!";
        } else {
            $error = "Database Error!";
        }
    } else {
        $error = "Failed to upload image.";
    }
}

// --- 2. HANDLE DELETE PRODUCT ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: manage_products.php"); // Refresh page
    exit();
}

// Fetch All Products
$products_result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .table-actions a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body class="admin-body">

    <aside class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="manage_orders.php"><i class="fas fa-box"></i> Manage Orders</a></li>
            <li><a href="manage_products.php" class="active"><i class="fas fa-utensils"></i> Manage Products</a></li>
            <li><a href="../index.php" target="_blank"><i class="fas fa-globe"></i> View Main Site</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="admin-main-content">
        <header class="flex between">
            <h1>Manage Products</h1>
            <div>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</div>
        </header>

        <div class="form-container">
            <h3 style="margin-bottom: 1rem; color: var(--lead);">Add New Food Item</h3>

            <?php if (isset($msg)) echo "<p style='color:green'>$msg</p>"; ?>
            <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="input-group">
                        <label>Food Name</label>
                        <input type="text" name="name" required placeholder="e.g. Cheese Burger">
                    </div>
                    <div class="input-group">
                        <label>Price ($ USD)</label>
                        <input type="number" step="0.01" name="price" required placeholder="e.g. 5.99">
                    </div>
                    <div class="input-group">
                        <label>Category</label>
                        <select name="category" style="width:100%; padding:1rem; border-radius:0.5rem; border:1px solid #ddd;">
                            <option value="Burger">Burger</option>
                            <option value="Pizza">Pizza</option>
                            <option value="Chicken">Chicken</option>
                            <option value="Chinese">Chinese</option>
                            <option value="Indian">Indian</option>
                            <option value="Drinks">Drinks</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Image</label>
                        <input type="file" name="image" required style="padding: 0.7rem;">
                    </div>
                </div>
                <button type="submit" name="add_product" class="btn" style="margin-top: 1rem;">Add Product</button>
            </form>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Img</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price ($)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products_result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../<?php echo $row['image']; ?>" class="product-img"></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td class="table-actions">
                            <a href="manage_products.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </main>
</body>

</html>