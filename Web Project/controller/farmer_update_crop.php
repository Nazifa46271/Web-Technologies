<?php
// Start session and connect to the database
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'You must be logged in to update crops.']));
}

// Create database connection
$conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');

// Check connection
if (!$conn) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()]));
}

// Check if required POST data is present
if (
    isset($_POST['crop_id']) && 
    isset($_POST['crop_name']) && 
    isset($_POST['description']) && 
    isset($_POST['quantity']) && 
    isset($_POST['available_quantity']) && 
    isset($_POST['price'])
) {
    // Get and sanitize input values
    $crop_id = intval($_POST['crop_id']);
    $crop_name = mysqli_real_escape_string($conn, $_POST['crop_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = intval($_POST['quantity']);
    $available_quantity = intval($_POST['available_quantity']);
    $price = floatval($_POST['price']);

    // Check if crop belongs to the logged-in farmer
    $farmer_id = $_SESSION['user_id'];
    $check_sql = "SELECT * FROM crop WHERE crop_id = $crop_id AND farmer_id = $farmer_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // Update crop details
        $update_sql = "UPDATE crop 
                       SET crop_name = '$crop_name', 
                           description = '$description', 
                           quantity = $quantity, 
                           available_quantity = $available_quantity, 
                           price = $price 
                       WHERE crop_id = $crop_id";

        if (mysqli_query($conn, $update_sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Crop updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating crop: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Crop not found or does not belong to you.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
}

// Close the database connection
mysqli_close($conn);
?>
