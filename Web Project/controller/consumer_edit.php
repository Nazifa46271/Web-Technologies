<?php
require_once('../model/database.php');
require_once('../controller/consumer_edit_controller.php');
include('../view/navbar.php');
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'Consumer') {
    header('Location: ../view/login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Consumer Profile</title>
    <script src="../asset/consumer_edit.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input, textarea, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="text"], input[type="email"], input[type="date"], textarea {
            background-color: #f9f9f9;
        }

        button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            text-decoration: none;
            color: #4CAF50;
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Consumer Profile</h1>

        <form name="editProfileForm" action="consumer_edit_controller.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?php echo $first_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?php echo $last_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $email; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="text" name="mobile" value="<?php echo $mobile; ?>" required>
            </div>

            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" name="country" value="<?php echo $country; ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea name="address" required><?php echo $address; ?></textarea>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" value="<?php echo $dob; ?>" required>
            </div>

            <!-- <div class="form-group">
                <label for="profile_image">Upload New Profile Image (optional):</label>
                <input type="file" name="profile_image" accept="image/*">
            </div> -->

            <button type="submit">Update Profile</button>
        </form>

        <a href="consumer_view.php">Back to Profile</a>
    </div>
</body>
</html>
