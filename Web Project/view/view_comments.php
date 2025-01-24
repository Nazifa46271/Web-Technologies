<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || !isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.html');
    exit;
}

// Check if post_id is set in the session
if (!isset($_SESSION['post_id'])) {
    // Redirect to the dashboard if no post_id is available
    header('Location: student_dashboard.php?error=missing_post_id');
    exit;
}

// Retrieve the post_id from the session
$postId = $_SESSION['post_id'];

// Retrieve session variables
$userId = $_SESSION['user_id'];
$userType = $_SESSION['role'];

// Include the database model
include('../model/database.php');

// Include the navbar
include('student_navbar.php');

// Fetch post details from the database
$conn = getConnection();
$stmt = $conn->prepare("SELECT content, created_at FROM posts WHERE post_id = ?");
$stmt->bind_param('i', $postId);
$stmt->execute();
$postResult = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch all comments related to this post
$commentsStmt = $conn->prepare("SELECT user_id, user_type, comment_text, comment_date FROM post_comments WHERE post_id = ? ORDER BY comment_date ASC");
$commentsStmt->bind_param('i', $postId);
$commentsStmt->execute();
$commentsResult = $commentsStmt->get_result();
$comments = $commentsResult->fetch_all(MYSQLI_ASSOC);
$commentsStmt->close();

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $commentText = trim($_POST['comment_text']);

    if (!empty($commentText)) {
        $insertStmt = $conn->prepare("INSERT INTO post_comments (post_id, user_id, user_type, comment_text) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param('iiss', $postId, $userId, $userType, $commentText);
        $insertStmt->execute();
        $insertStmt->close();

        // Set a session variable for success message
        $_SESSION['success'] = "Comment submitted successfully!";
        
        // Refresh the same page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = "Comment cannot be empty!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Comment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="container mx-auto my-8">
        <h1 class="text-3xl font-bold mb-4">Post Details</h1>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-800 mb-2"><?php echo htmlspecialchars($postResult['content']); ?></p>
            <p class="text-sm text-gray-500">Posted on: <?php echo htmlspecialchars($postResult['created_at']); ?></p>
        </div>

        <h2 class="text-xl font-semibold mt-8 mb-4">Comments</h2>
        
        <!-- Success Alert -->
        <?php if (isset($_SESSION['success'])) : ?>
            <div id="success-alert" class="bg-green-500 text-white p-4 rounded-lg mb-4">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']); // Clear the success message
                ?>
            </div>
            <script>
                setTimeout(() => {
                    const alert = document.getElementById('success-alert');
                    if (alert) alert.style.display = 'none';
                }, 2000);
            </script>
        <?php endif; ?>

        <!-- Display Comments -->
        <div class="space-y-4">
            <?php if (!empty($comments)) : ?>
                <?php foreach ($comments as $comment) : ?>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p class="text-gray-800"><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                        <div class="text-sm text-gray-500">
                            <p><strong>Commented by:</strong>
                                <?php 
                                // Get name based on user type
                                if ($comment['user_type'] === 'Student') {
                                    $name = getStudentNameById($comment['user_id']);
                                } elseif ($comment['user_type'] === 'Consumer') {
                                    $name = getConsumerNameById($comment['user_id']);
                                } elseif ($comment['user_type'] === 'Farmer') {
                                    $name = getFarmerNameById($comment['user_id']);
                                } else {
                                    $name = "Unknown User";
                                }

                                if ($name) {
                                    echo htmlspecialchars($name);
                                } else {
                                    echo "Name not available";  // Fallback if the name is not found
                                }
                                ?> 
                            </p>
                            <p><strong>Commented on:</strong> <?php echo htmlspecialchars($comment['comment_date']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-gray-600">No comments available yet.</p>
            <?php endif; ?>
        </div>

        <!-- Comment Form -->
        <form method="POST" class="mt-8 bg-white p-4 rounded-lg shadow">
            <div class="mb-4">
                <label for="comment_text" class="block text-gray-700 font-semibold mb-2">Add a Comment:</label>
                <textarea name="comment_text" id="comment_text" rows="4" class="w-full border rounded-lg p-2" placeholder="Write your comment here..."></textarea>
            </div>
            <?php if (isset($error)) : ?>
                <p class="text-red-500 mb-4"><?php echo $error; ?></p>
            <?php endif; ?>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none">
                Submit Comment
            </button>
        </form>
    </div>
</body>
</html>
