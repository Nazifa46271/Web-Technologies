<?php
session_start();
require_once('../model/database.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'You must be logged in to edit your cart.']);
    exit;
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Get POST data
$cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
$crop_id = isset($_POST['crop_id']) ? intval($_POST['crop_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Ensure that the quantity is a valid number and does not exceed available quantity
if ($quantity < 1) {
    echo json_encode(['success' => false, 'error' => 'Quantity must be at least 1.']);
    exit;
}

// Fetch the crop details from the model
$cropDetails = getCropDetails($crop_id);

if ($cropDetails) {
    $price_per_kg = $cropDetails['price'];
    $available_quantity = $cropDetails['available_quantity'];

    if ($quantity > $available_quantity) {
        echo json_encode(['success' => false, 'error' => 'Quantity exceeds available stock.']);
        exit;
    }

    // Calculate total price
    $total_price = $quantity * $price_per_kg;

    // Update the cart using the model
    if (updateCart($cart_id, $user_id, $quantity, $total_price)) {
        // Send the updated price back as JSON
        echo json_encode(['success' => true, 'total_price' => $total_price]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Could not update cart.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Crop not found.']);
}
?>
