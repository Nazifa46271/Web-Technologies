<?php
session_start();
require_once('../model/database.php');
include('navbar.php');

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
    $payment_type = "Mobile Banking"; // Ensure payment_type is set

    // Fetch crop details
    $conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT crop_name FROM crop WHERE crop_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $crop_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $crop = mysqli_fetch_assoc($result);
        $crop_name = $crop['crop_name'] ?? "Unknown";
        mysqli_stmt_close($stmt);
    } else {
        die("Failed to prepare the query: " . mysqli_error($conn));
    }

    mysqli_close($conn);
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
    <script src="../asset/mobile_payment.js"></script>
    <style>
        /* General reset */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        /* Prevent heading from being hidden behind navbar */
        body {
            padding-top: 60px; /* Adjust based on navbar height */
        }

        h1 {
            text-align: center;
            margin: 30px 0;
            color: #444;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .details {
            margin-bottom: 30px;
            color: #555;
            font-size: 16px;
        }

        .details p {
            margin: 10px 0;
        }

        .details p strong {
            color: #222;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
            color: #777;
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

            <label for="bank_account">Mobile Banking Account Number:</label>
            <input type="text" id="bank_account" name="bank_account" pattern="\d{11,}" minlength="8" maxlength="15" required placeholder="Enter mobile banking number">
            <button type="submit">Confirm Payment</button>
        </form>
    </div>
    <footer>&copy; <?php echo date('Y'); ?> Agriculture Payment System</footer>
</body>
</html>

