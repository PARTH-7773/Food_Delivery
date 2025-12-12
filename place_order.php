<?php
// place_order.php

// 1. START SESSION TO GET LOGGED IN USER ID
session_start();
include 'db.php';

// --- SECURITY CHECK ---
if (!isset($_SESSION['user_id'])) {
    // Reject the request if not logged in
    echo json_encode(['success' => false, 'message' => 'Login Required']);
    exit;
}

// Get User ID from Session
$user_id = $_SESSION['user_id'];

// Get the raw POST data
$json = file_get_contents('php://input');
$data = json_decode($json);

if (!$data || !isset($data->customer) || !isset($data->cart)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

// 2. GET USER ID (If not logged in, set to 0 or handle error)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

$customer = $data->customer;
$cart = $data->cart;
$totalAmount = 0;

foreach ($cart as $item) {
    $totalAmount += (float)$item->price * (int)$item->quantity;
}

$conn->begin_transaction();

try {
    // 3. INSERT WITH USER_ID
    // We added 'user_id' to the query
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_address, customer_phone, total_amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssd", $user_id, $customer->name, $customer->address, $customer->phone, $totalAmount);
    $stmt->execute();

    $order_id = $conn->insert_id;

    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt_items->bind_param("iiid", $order_id, $item->id, $item->quantity, $item->price);
        $stmt_items->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Order saved.']);
} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $exception->getMessage()]);
}

$stmt->close();
$stmt_items->close();
$conn->close();
