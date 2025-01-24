<?php
session_start();
require_once('../model/database.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'You are not logged in.']);
    exit;
}

// Ensure purchase details exist in the session
if (isset($_SESSION['purchase'])) {
    $purchase = $_SESSION['purchase'];

    $crop_id = $purchase['crop_id'];
    $quantity = $purchase['quantity'];
    $total_price = $purchase['total_price'];

    // Fetch crop details
    $crop_name = fetchCropName($crop_id);
    if (!$crop_name) {
        echo json_encode(['message' => 'Invalid crop details.']);
        exit;
    }

    // Save crop name in session
    $_SESSION['purchase']['crop_name'] = $crop_name;
} else {
    echo json_encode(['message' => 'No purchase details found.']);
    exit;
}

// Handle payment selection via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['payment_type'])) {
        $payment_type = $_POST['payment_type'];

        // Save payment type in the session
        $_SESSION['purchase']['payment_type'] = $payment_type;

        // Determine redirection URL based on payment type
        $redirectUrl = $payment_type === 'retail' ? 'retails_payment.php' : 'mobile_payment.php';

        // Send JSON response for redirection
        echo json_encode(['redirect' => $redirectUrl]);
        exit;
    } else {
        echo json_encode(['message' => 'Payment type is required.']);
        exit;
    }
}

// If accessed directly (not via AJAX), send view data
return [
    'crop_name' => $crop_name,
    'quantity' => $quantity,
    'total_price' => $total_price
];
?>
