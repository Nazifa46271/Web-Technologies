<?php
// Start session and connect to the database
session_start();

// Create connection
$conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to add a crop.");
    }

    // Retrieve form data
    $farmer_id = $_SESSION['user_id'];
    $crop_name = htmlspecialchars($_POST['crop_name']);
    $description = htmlspecialchars($_POST['description']);
    $quantity = intval($_POST['quantity']);
    $available_quantity = intval($_POST['available_quantity']);
    $price = floatval($_POST['price']);

    // Handle file upload
    $image = $_FILES['image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif','image/jpg' ];
    $max_file_size = 2 * 1024 * 1024; // 2MB

    if (in_array($image['type'], $allowed_types) && $image['size'] <= $max_file_size) {
        $image_name = time() . '_' . basename($image['name']); // Unique name
        $image_path = '../asset/images/' . $image_name;

        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            // Use prepared statements to insert data into the database
            $sql = "INSERT INTO crop (farmer_id, crop_name, description, quantity, available_quantity, price, image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "isssids", $farmer_id, $crop_name, $description, $quantity, $available_quantity, $price, $image_name);

                if (mysqli_stmt_execute($stmt)) {
                    echo "Crop added successfully!";
                    header("Location: farmer_view_crop.php"); // Redirect to view crops page
                    exit;
                } else {
                    echo "Error inserting crop: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Error preparing statement: " . mysqli_error($conn);
            }
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Invalid file type or size. Only JPEG, PNG, and GIF files under 2MB are allowed.";
    }
} else {
    echo "Invalid request method.";
}
?>
