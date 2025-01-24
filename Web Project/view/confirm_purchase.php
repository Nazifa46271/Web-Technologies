<?php
$data = include('../controller/confirm_purchase_controller.php');
include('../view/navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Purchase</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../asset/confirm_purchase.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
            padding-top: 60px; /* Ensure the heading is not hidden by the navbar */
        }
        .container {
            max-width: 600px;
            background-color: #fff;
            margin: 30px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 10px 0;
            font-size: 16px;
            color: #555;
        }
        .info p strong {
            color: #000;
        }
        form {
            margin-top: 20px;
        }
        label {
            font-size: 16px;
            display: block;
            margin-bottom: 10px;
            cursor: pointer;
        }
        input[type="radio"] {
            margin-right: 10px;
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
        #responseMessage {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Confirm Your Purchase</h1>
    <div class="container">
        <div class="info">
            <p><strong>Crop Name:</strong> <?php echo htmlspecialchars($data['crop_name']); ?></p>
            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($data['quantity']); ?> kg</p>
            <p><strong>Total Price:</strong> $<?php echo htmlspecialchars(number_format((float)$data['total_price'], 2)); ?></p>
        </div>

        <!-- Payment options form -->
        <form id="paymentForm">
            <label>
                <input type="radio" name="payment_type" value="retail" required> Retail Bank
            </label>
            <label>
                <input type="radio" name="payment_type" value="mobile" required> Mobile Bank
            </label>
            <button type="submit">Proceed to Payment</button>
        </form>

        <div id="responseMessage"></div>
    </div>
</body>
</html>
