<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumer Profile</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #444;
        }

        /* Container */
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        /* Heading */
        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        /* Profile Image */
        img {
            border-radius: 50%;
            border: 4px solid #007bff;
            margin-bottom: 20px;
        }

        /* Profile Details */
        h2 {
            margin: 20px 0 10px;
            color: #007bff;
        }

        p {
            font-size: 16px;
            margin: 10px 0;
            color: #555;
        }

        strong {
            color: #222;
        }

        /* Form Styling */
        form {
            margin-top: 20px;
            text-align: left;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            margin: 10px 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Logout and Edit Profile Links */
        a {
            text-decoration: none;
        }

        a button {
            background-color: #28a745;
        }

        a button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Consumer Profile</h1>
        <img src="../asset/images/<?php echo $profile_image ? $profile_image : 'default.jpg'; ?>" 
             alt="Profile Picture" width="150" height="150">

        <form action="consumer_view.php" method="POST" enctype="multipart/form-data">
            <label for="profile_image">
                <?php echo $profile_image ? 'Change Profile Photo:' : 'Upload a New Profile Image:'; ?>
            </label>
            <input type="file" name="profile_image" accept="image/*" required>
            <button type="submit"><?php echo $profile_image ? 'Change Image' : 'Upload Image'; ?></button>
        </form>

        <h2>Profile Details</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($first_name . " " . $last_name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Mobile:</strong> <?php echo htmlspecialchars($mobile); ?></p>
        <p><strong>Country:</strong> <?php echo htmlspecialchars($country); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($dob); ?></p>

        <a href="consumer_edit.php"><button>Edit Profile</button></a>
        <!-- <a href="logout.php"><button>Logout</button></a> -->
    </div>
</body>
</html>
