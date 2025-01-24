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
    $delete_query = "DELETE FROM consumer WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: consumer_details.php"); // Refresh the page after deletion
    } else {
        die("Error deleting consumer: " . mysqli_error($conn));
    }
}

// Handle edit request
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $query = "SELECT * FROM consumer WHERE id = $edit_id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error fetching consumer details: " . mysqli_error($conn));
    }
    $consumer = mysqli_fetch_assoc($result);
}

// Update consumer data after form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_id'])) {
    $id = $_POST['update_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $role = $_POST['role'];

    // For simplicity, handling profile image upload
    $profile_image = $_FILES['profile_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($profile_image);
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);

    $updateQuery = "UPDATE consumer SET first_name='$firstName', last_name='$lastName', email='$email', mobile='$mobile', password='$password', country='$country', address='$address', dob='$dob', role='$role', profile_image='$profile_image' WHERE id=$id";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: consumer_details.php"); // Refresh the page after updating
    } else {
        die("Error updating data: " . mysqli_error($conn));
    }
}

// Query to fetch all consumers' details
$query = "SELECT * FROM consumer";
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
    <title>Consumer Details</title>
    <style>
        /* Same styling as in your original code */
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
    <h1>Consumer Details</h1>
    <input
        type="text"
        id="search-box"
        onkeyup="searchConsumers(this.value)"
        placeholder="Search consumers by name, email, or country..."
    />

    <?php
    // If editing, show the edit form
    if (isset($consumer)) {
        ?>
        <div id="edit-form" class="form-container">
            <h2>Edit Consumer</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($consumer['id']); ?>">
                <label>First Name:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($consumer['first_name']); ?>" required><br>
                <label>Last Name:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($consumer['last_name']); ?>" required><br>
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($consumer['email']); ?>" required><br>
                <label>Mobile:</label>
                <input type="text" name="mobile" value="<?php echo htmlspecialchars($consumer['mobile']); ?>" required><br>
                <label>Password:</label>
                <input type="password" name="password" value="<?php echo htmlspecialchars($consumer['password']); ?>" required><br>
                <label>Country:</label>
                <input type="text" name="country" value="<?php echo htmlspecialchars($consumer['country']); ?>" required><br>
                <label>Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($consumer['address']); ?>" required><br>
                <label>Date of Birth:</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($consumer['dob']); ?>" required><br>
                <label>Role:</label>
                <input type="text" name="role" readonly value="<?php echo htmlspecialchars($consumer['role']); ?>" required><br>
                <label>Profile Image:</label>
                <input type="file" name="profile_image"><br>
                <button type="submit">Update Consumer</button>
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
                <th>Profile Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display all consumers' details with Edit and Delete buttons
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
                echo "<td><img src='uploads/" . htmlspecialchars($row['profile_image']) . "' width='50' height='50'></td>";
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
