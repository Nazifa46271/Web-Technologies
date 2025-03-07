<?php
session_start();
require_once('../model/database.php'); 

// Validate input from AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array('success' => false, 'message' => '');

    // Get the data sent via AJAX
    $email = $_POST['email'];
    $recent_password = $_POST['recent_password'];
    $new_password = $_POST['new_password'];

    $loggedInEmail = $_SESSION['email'];

    if ($email !== $loggedInEmail) {
        $response['message'] = "Incorrect email. You can only change the password for the logged-in account.";
    } else {
        // Password validation: at least 5 characters, one uppercase, one lowercase, one number
        if (!preg_match("/[A-Z]/", $new_password) || 
            !preg_match("/[a-z]/", $new_password) || 
            !preg_match("/[0-9]/", $new_password) || 
            strlen($new_password) < 5) {
            
            $response['message'] = "Password should be at least 5 characters long and include one uppercase letter, one lowercase letter, and one number.";
        } else {
            // Fetch user data from the database
            $consumerData = fetchConsumerByEmail($email);

            if ($consumerData) {
                if ($recent_password === $consumerData['password']) {
                    // Update password
                    if (updatePassword($email, $new_password)) {
                        $response['success'] = true;
                        $response['message'] = "Password has been updated successfully.";
                    } else {
                        $response['message'] = "Error updating password.";
                    }
                } else {
                    $response['message'] = "Recent password is incorrect.";
                }
            } else {
                $response['message'] = "No account found with that email.";
            }
        }
    }

    // Return the response as JSON
    echo json_encode($response);
}
?>
