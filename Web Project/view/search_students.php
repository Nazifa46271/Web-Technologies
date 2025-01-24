<?php
// Establish a database connection
$conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search query
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Sanitize the input
$searchQuery = mysqli_real_escape_string($conn, $searchQuery);

// Query to search students based on input
$query = "SELECT * FROM student 
          WHERE first_name LIKE '%$searchQuery%' 
          OR last_name LIKE '%$searchQuery%' 
          OR email LIKE '%$searchQuery%' 
          OR country LIKE '%$searchQuery%'";
$result = mysqli_query($conn, $query);

// Check if query execution was successful
if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}

// Generate the table rows for matching students
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
    echo "</tr>";
}

// Close the database connection
mysqli_close($conn);
?>
