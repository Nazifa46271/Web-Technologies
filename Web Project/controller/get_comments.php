<?php
// Include the database connection
include_once('../model/database.php');

// Check if post_id is provided
if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];

    // Connect to the database
    $conn = getConnection();

    // Fetch comments for the given post_id
    $stmt = $conn->prepare("SELECT c.comment_text, c.comment_date, u.user_name, u.user_type 
                            FROM post_comments c 
                            JOIN users u ON c.user_id = u.id 
                            WHERE c.post_id = ? ORDER BY c.comment_date ASC");
    $stmt->bind_param("i", $postId);  // Bind the post_id parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the comments into an array
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    // Return the comments as a JSON response
    echo json_encode($comments);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([]);
}
?>
