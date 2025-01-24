<?php
// Establishing the database connection
$conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to count the total number of farmers
$query_farmers = "SELECT COUNT(*) AS total_farmers FROM farmer";
$result_farmers = mysqli_query($conn, $query_farmers);

// Fetch the result for farmers
if ($result_farmers) {
    $row_farmers = mysqli_fetch_assoc($result_farmers);
    $totalFarmers = $row_farmers['total_farmers'];
} else {
    $totalFarmers = "Error fetching data: " . mysqli_error($conn);
}

// Query to count the total number of students
$query_students = "SELECT COUNT(*) AS total_students FROM student";
$result_students = mysqli_query($conn, $query_students);

// Fetch the result for students
if ($result_students) {
    $row_students = mysqli_fetch_assoc($result_students);
    $totalStudents = $row_students['total_students'];
} else {
    $totalStudents = "Error fetching data: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

</head>
<body>
    <header>

    </header>

    <main> 
        <section>
            <h1>User Details:</h1>
            <div class="">

            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <div class="footer">
        <p>&copy; 2025 Your Company. All Rights Reserved.</p>
    </div>

</body>
</html>
