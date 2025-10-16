<?php
// place_order.php

include 'db.php';

// Get the raw POST data from the request
$json = file_get_contents('php://input');
// Decode the JSON data into a PHP object
$data = json_decode($json);

// Check if data is valid
if (!$data || !isset($data->customer) || !isset($data->cart)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

// Extract data
$customer = $data->customer;
$cart = $data->cart;
$totalAmount = 0;

// Calculate total amount from the cart on the server-side for security
foreach ($cart as $item) {
    $totalAmount += (float)$item->price * (int)$item->quantity;
}

// Start a database transaction
$conn->begin_transaction();

try {
    // 1. Insert into the 'orders' table
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_address, customer_phone, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $customer->name, $customer->address, $customer->phone, $totalAmount);
    $stmt->execute();
    
    // Get the ID of the order we just created
    $order_id = $conn->insert_id;

    // 2. Insert each item into the 'order_items' table
    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt_items->bind_param("iiid", $order_id, $item->id, $item->quantity, $item->price);
        $stmt_items->execute();
    }

    // If everything was successful, commit the transaction
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);

} catch (mysqli_sql_exception $exception) {
    // If anything failed, roll back the transaction
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to place order.']);
}

$stmt->close();
$stmt_items->close();
$conn->close();
?>