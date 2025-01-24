<?php
session_start();
require_once('../model/database.php'); // Make sure we have the functions from database.php

if (isset($_POST['submit_comment']) && isset($_SESSION['user_id'])) {
    $videoId = $_SESSION['video_id'];
    $userId = $_SESSION['user_id'];
    $commentText = $_POST['comment_text'];
    $userType = $_SESSION['role'];

    // Insert the comment into the database using the new function
    insertComment($videoId, $userId, $userType, $commentText);

    // Fetch the latest comment details from the database using the new function
    $comment = fetchLatestComment($videoId);

    if ($comment) {
        // Return the comment data as JSON
        echo json_encode([ 
            'status' => 'success',
            'comment_text' => htmlspecialchars($comment['comment_text']),
            'comment_date' => date("F j, Y, g:i a", strtotime($comment['comment_date'])),
            'first_name' => htmlspecialchars($comment['first_name']),
            'last_name' => htmlspecialchars($comment['last_name']),
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit();
}
?>