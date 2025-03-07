<?php
// Establishing the database connection
$conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search query
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Sanitize the input
$searchQuery = mysqli_real_escape_string($conn, $searchQuery);

// Query to search farmers based on the input
$query = "SELECT * FROM farmer 
          WHERE first_name LIKE '%$searchQuery%' 
          OR last_name LIKE '%$searchQuery%' 
          OR email LIKE '%$searchQuery%' 
          OR country LIKE '%$searchQuery%'";
$result = mysqli_query($conn, $query);

// Check for errors in query execution
if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}

// Generate the HTML rows for the matching farmers
if (mysqli_num_rows($result) > 0) {
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
} else {
    echo "<tr><td colspan='9'>No farmers found matching the search query.</td></tr>";
}

// Close the database connection
mysqli_close($conn);
?>
