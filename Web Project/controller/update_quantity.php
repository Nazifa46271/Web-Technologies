<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $crop_id = intval($_POST['crop_id']); // Ensure crop_id is sanitized as an integer
    $available_quantity = intval($_POST['available_quantity']); // Ensure available_quantity is an integer

    if ($available_quantity < 0) {
        echo json_encode(['success' => false, 'message' => 'Quantity cannot be negative']);
        exit();
    }

    // Database connection
    $conn = new mysqli('127.0.0.1', 'root', '', 'agriculture');
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
    }

    // Update the database with the new quantity
    $query = "UPDATE crop SET available_quantity = ? WHERE crop_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare query: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("ii", $available_quantity, $crop_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Quantity updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
    }

    // Clean up resources
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
