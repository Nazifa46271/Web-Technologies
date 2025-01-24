<?php
// Establishing the database connection
$conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM farmer WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: farmer_details.php"); // Refresh the page after deletion
    } else {
        die("Error deleting farmer: " . mysqli_error($conn));
    }
}

// Handle edit request
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $query = "SELECT * FROM farmer WHERE id = $edit_id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error fetching farmer details: " . mysqli_error($conn));
    }
    $farmer = mysqli_fetch_assoc($result);
}

// Update farmer data after form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_id'])) {
    $id = $_POST['update_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $role = $_POST['role'];

    $updateQuery = "UPDATE farmer SET first_name='$firstName', last_name='$lastName', email='$email', mobile='$mobile', country='$country', address='$address', dob='$dob', role='$role' WHERE id=$id";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: farmer_details.php"); // Refresh the page after updating
    } else {
        die("Error updating data: " . mysqli_error($conn));
    }
}

// Query to fetch all farmers' details
$query = "SELECT * FROM farmer";
$result = mysqli_query($conn, $query);

// Check for errors
if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
        }

        #search-box {
            margin-bottom: 20px;
            padding: 10px;
            width: 50%;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
        }

        .action-buttons a {
            margin-left: 10px;
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
        }

        .action-buttons a:hover {
            background-color: #0056b3;
        }

        .action-buttons .delete-btn {
            background-color: #dc3545;
        }

        .action-buttons .delete-btn:hover {
            background-color: #c82333;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 20px;
            display: none; /* Initially hidden */
        }

        .form-container.show {
            display: block;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .cancel-btn {
            background-color: #f0ad4e;
        }

        .cancel-btn:hover {
            background-color: #ec971f;
        }

    </style>
    <script>
        // Toggle the visibility of the edit form
        function toggleEditForm() {
            var form = document.getElementById('edit-form');
            form.classList.toggle('show');
        }
    </script>
</head>
<body>
    <h1>Farmer Details</h1>
    <input
        type="text"
        id="search-box"
        onkeyup="searchFarmers(this.value)"
        placeholder="Search farmers by name, email, or country..."
    />

    <?php
    // If editing, show the edit form
    if (isset($farmer)) {
        ?>
        <div id="edit-form" class="form-container">
            <h2>Edit Farmer</h2>
            <form method="POST">
                <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($farmer['id']); ?>">
                <label>First Name:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($farmer['first_name']); ?>" required><br>
                <label>Last Name:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($farmer['last_name']); ?>" required><br>
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($farmer['email']); ?>" required><br>
                <label>Mobile:</label>
                <input type="text" name="mobile" value="<?php echo htmlspecialchars($farmer['mobile']); ?>" required><br>
                <label>Country:</label>
                <input type="text" name="country" value="<?php echo htmlspecialchars($farmer['country']); ?>" required><br>
                <label>Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($farmer['address']); ?>" required><br>
                <label>Date of Birth:</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($farmer['dob']); ?>" required><br>
                <label>Role:</label>
                <input type="text" name="role" readonly value="<?php echo htmlspecialchars($farmer['role']); ?>" required><br>
                <button type="submit">Update Farmer</button>
                <button type="button" class="cancel-btn" onclick="toggleEditForm()">Cancel</button>
            </form>
        </div>
        <script>
            // Display the edit form
            toggleEditForm();
        </script>
        <?php
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Country</th>
                <th>Address</th>
                <th>DOB</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display all farmers' details with Edit and Delete buttons
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['mobile']) . "</td>";
                echo "<td>" . htmlspecialchars($row['country']) . "</td>";
                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                echo "<td class='action-buttons'>
                        <a href='?edit_id=" . $row['id'] . "'>Edit</a>
                        <a href='?delete_id=" . $row['id'] . "' class='delete-btn'>Delete</a>
                    </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
