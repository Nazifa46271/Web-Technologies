<?php
// Start the session
session_start();

// Check if the form was submitted and post_id is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    // Sanitize the input
    $postId = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    // Save the post_id to the session
    $_SESSION['post_id'] = $postId;

    // Redirect to the view_comment.php page
    header('Location: ../view/view_comments.php');
    exit;
} else {
    // If post_id is not set, redirect back to the dashboard with an error message
    header('Location: ../view/student_dashboard.php?error=invalid_post');
    exit;
}
