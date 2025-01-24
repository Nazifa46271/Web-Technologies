<?php
session_start();
require_once('../model/database.php');
include('../view/navbar.php');

// Ensure user is logged in as a 'Consumer'
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'Consumer') {
    header('Location: ../view/login.html');
    exit();
}

$crop_details = null;
$crop_reviews = [];

// Handle data sent via AJAX from buy_product.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['crop_id'] = $_POST['crop_id'];
    $_SESSION['crop_name'] = $_POST['crop_name'];
    $_SESSION['description'] = $_POST['description'];
    $_SESSION['price'] = $_POST['price'];
    $_SESSION['image'] = $_POST['image'];

    if (isset($_POST['available_quantity'])) {
        $_SESSION['available_quantity'] = $_POST['available_quantity'];
    }
}

if (isset($_SESSION['crop_id'])) {
    $crop_details = [
        'crop_id' => $_SESSION['crop_id'],
        'crop_name' => $_SESSION['crop_name'],
        'description' => $_SESSION['description'],
        'price' => $_SESSION['price'],
        'image_path' => "/images/" . basename($_SESSION['image']), // Ensure relative path
        'available_quantity' => $_SESSION['available_quantity'] ?? 0,
    ];

    // Connect to the database
    $conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $crop_id = mysqli_real_escape_string($conn, $_SESSION['crop_id']);
    $query = "SELECT * FROM crop WHERE crop_id = $crop_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $crop_details_db = mysqli_fetch_assoc($result);
        $crop_details['image_path'] = "../asset/images/" . basename($crop_details_db['image']);
        $crop_details['available_quantity'] = $crop_details_db['available_quantity'];
    } else {
        $crop_details = null;
    }

    // Fetch reviews for the crop
    $query_reviews = "
        SELECT r.id, r.crop_id, r.user_id, r.user_type, r.review_text, r.review_date, 
               CONCAT(c.first_name, ' ', c.last_name) AS user_name
        FROM crop_review r
        JOIN consumer c ON r.user_id = c.id
        WHERE r.crop_id = $crop_id
        ORDER BY r.review_date DESC";
    
    $result_reviews = mysqli_query($conn, $query_reviews);

    if ($result_reviews && mysqli_num_rows($result_reviews) > 0) {
        while ($review = mysqli_fetch_assoc($result_reviews)) {
            $crop_reviews[] = $review;
        }
    }

    mysqli_close($conn);
} else {
    $crop_details = null;
    $crop_reviews = [];
}

// Pass the updated data to the view
require_once('../view/product_details.php');
?>
