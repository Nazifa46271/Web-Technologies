<?php
session_start();
require_once('../model/database.php');
include('navbar.php');
$crop_name = isset($_SESSION['purchase']['crop_name']) ? $_SESSION['purchase']['crop_name'] : 'Unknown Crop';

// Use $crop_name for displaying on the page

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit;
}

// Retrieve purchase details from session
if (isset($_SESSION['purchase'])) {
    $purchase = $_SESSION['purchase'];
    $crop_id = $purchase['crop_id'];
    $quantity = $purchase['quantity'];
    $total_price = $purchase['total_price'];
    $payment_type = "Retail Banking"; // Ensure payment_type is set
    $crop_name = htmlspecialchars($purchase['crop_name']); // Assuming you have crop_name in session
} else {
    echo "No purchase details found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($payment_type); ?> Payment</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../asset/retail_payment.js"></script>
    <style>
        /* Reset some basic styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
            padding-top: 60px; /* Offset for navbar */
        }

        .container {
            max-width: 600px;
            background-color: #fff;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .details {
            margin-bottom: 20px;
        }

        .details p {
            font-size: 16px;
            margin: 10px 0;
            color: #555;
        }

        .details p strong {
            color: #000;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="text"]:focus {
            border-color: #28a745;
            outline: none;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
        }

        button {
            width: 100%;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 24px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1><?php echo ucfirst($payment_type); ?> Payment</h1>
    <div class="container">
        <div class="details">
            <p><strong>Crop Name:</strong> <?php echo htmlspecialchars($crop_name); ?></p>
            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($quantity); ?> kg</p>
            <p><strong>Total Price:</strong> $<?php echo htmlspecialchars(number_format($total_price, 2)); ?></p>
        </div>

        <form id="paymentForm">
            <input type="hidden" id="crop_id" value="<?php echo htmlspecialchars($crop_id); ?>">
            <input type="hidden" id="quantity" value="<?php echo htmlspecialchars($quantity); ?>">
            <input type="hidden" id="total_price" value="<?php echo htmlspecialchars($total_price); ?>">
            <input type="hidden" id="payment_type" value="<?php echo htmlspecialchars($payment_type); ?>">

            <label for="bank_account">Bank Account Number:</label>
            <input type="text" id="bank_account" name="bank_account" pattern="\d{8,}" minlength="8" maxlength="15" required placeholder="Enter bank account number">
            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
