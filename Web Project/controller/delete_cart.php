<?php
session_start();
require_once('../model/database.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'You must be logged in to modify your cart.']);
    exit();
}

// Fetch cart_id from POST request
$cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;

// Call model function to delete the cart item
if (deleteCartItem($cart_id, $_SESSION['user_id'])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not delete the item.']);
}
?>
