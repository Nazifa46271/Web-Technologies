<?php
session_start();
require_once('../model/database.php');

header('Content-Type: application/json'); // Return JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if we can correctly get JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if the email and password are set in the received JSON
    if (isset($data['email']) && isset($data['password'])) {
        $email = trim($data['email']);
        $password = trim($data['password']);

        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'error' => 'Email and Password are required.']);
            exit;
        }

        // Function to authenticate user (this should return user role or false)
        $role = authenticateUser($email, $password);

        if ($role) {
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            // Assuming getUserIdByEmail returns user ID based on the email and role
            $userId = getUserIdByEmail($email, $role);
            $_SESSION['user_id'] = $userId;

            // Redirect URL based on role
            $redirectUrl = '';
            if ($role == "Consumer") {
                $redirectUrl = "../view/consumerDashboard.php";
            } elseif ($role == "Student") {
                $redirectUrl = "../view/student_dashboard.php";
            } elseif ($role == "Farmer") {
                $redirectUrl = "../view/farmer_menu.php";
            }

            echo json_encode(['success' => true, 'redirectUrl' => $redirectUrl]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid Email or Password!']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing email or password.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
