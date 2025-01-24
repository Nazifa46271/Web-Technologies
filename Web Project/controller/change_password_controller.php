<?php
require_once '../model/database.php'; // Correct path to the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive input
    $email = $_POST['email'];
    $recent_password = $_POST['recent_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Validate new password and confirm password
    if ($new_password !== $confirm_new_password) {
        echo json_encode(["success" => false, "error" => "New password and confirm password do not match."]);
        exit;
    }

    // Password validation - 5 characters, 1 uppercase, 1 lowercase, 1 number
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{5,}$/', $new_password)) {
        echo json_encode(["success" => false, "error" => "Password must be at least 5 characters long, containing at least one uppercase letter, one lowercase letter, and one number."]);
        exit;
    }

    // Check each table for the email
    $tables = ['Farmer', 'Student', 'Consumer'];
    $passwordChanged = false;

    foreach ($tables as $table) {
        if (verifyAndChangePassword($table, $email, $recent_password, $new_password)) {
            $passwordChanged = true;
            echo json_encode(["success" => true, "message" => "Password updated successfully for $table."]);
            break;
        }
    }

    if (!$passwordChanged) {
        echo json_encode(["success" => false, "error" => "Invalid email or recent password."]);
    }
}
?>
