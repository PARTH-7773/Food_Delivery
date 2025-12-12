<?php
// get_products.php

include 'db.php';
header('Content-Type: application/json');

// Set a conversion rate (e.g., 1 USD = 80 INR)
$usd_to_inr_rate = 80;

$sql = "SELECT id, name, price, image, category FROM products ";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Convert the price from USD to INR
        $row['price'] = (float)$row['price'] * $usd_to_inr_rate;
        $products[] = $row;
    }
}

echo json_encode($products);
$conn->close();
?>