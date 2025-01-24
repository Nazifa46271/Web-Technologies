<?php
// Establish database connection
$conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission to update student data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $role = $_POST['role'];

    // Update the student record
    $updateQuery = "UPDATE student SET first_name = '$firstName', last_name = '$lastName', email = '$email', mobile = '$mobile', country = '$country', address = '$address', dob = '$dob', role = '$role' WHERE id = '$studentId'";

    if (mysqli_query($conn, $updateQuery)) {
        echo "Student updated successfully!";
    } else {
        echo "Error: " . $updateQuery . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
