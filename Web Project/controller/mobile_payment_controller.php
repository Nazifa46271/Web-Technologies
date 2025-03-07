<?php

header("Content-Type: application/json");
session_start();

require_once('../model/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract data from POST request
    $crop_id = $data['crop_id'] ?? null;
    $quantity = $data['quantity'] ?? null;
    $total_price = $data['total_price'] ?? null;
    $bank_account = $data['bank_account'] ?? null;
    $payment_type = $data['payment_type'] ?? 'Retail';

    // Input validation
    if (!$crop_id || !$quantity || !$total_price || !$bank_account) {
        echo json_encode(['success' => false, 'error_message' => 'Invalid request data.']);
        exit;
    }

    // Bank account number validation
    if (!preg_match("/^\d{8,15}$/", $bank_account)) {
        echo json_encode(['success' => false, 'error_message' => 'Invalid bank account number.']);
        exit;
    }

    // Get user info from session
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['success' => false, 'error_message' => 'User not logged in.']);
        exit;
    }

    // Get user's account balance
    $account = getUserBalance($user_id);
    if (!$account || $account['balance'] < $total_price) {
        echo json_encode(['success' => false, 'error_message' => 'Insufficient funds.']);
        exit;
    }

    // Record purchase
    if (recordPurchase($user_id, $crop_id, $quantity, $payment_type)) {
        // Deduct balance
        if (updateBalance($user_id, $total_price)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error_message' => 'Failed to update balance.']);
        }
    } else {
        echo json_encode(['success' => false, 'error_message' => 'Failed to record purchase.']);
    }
}
?>
