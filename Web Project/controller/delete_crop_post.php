<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli('127.0.0.1', 'root', '', 'agriculture');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Get the crop ID from the request body
$data = json_decode(file_get_contents("php://input"), true);
$crop_id = $data['id'] ?? null;

if ($crop_id) {
    // Prepare and execute the delete query
    $sql = "DELETE FROM crop_exchange WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $crop_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete post']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No valid crop ID provided']);
}

$conn->close();
?>
