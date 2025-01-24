 <?php
// Start the session to access session variables
session_start();

// Include the database connection
include_once('../model/database.php');


// Check if the user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header('Location: login.html');
    exit;
}

// Get the user's email from the session
$email = $_SESSION['email'];
$role = $_SESSION['role'];

// Initialize a variable to hold the posts
$posts = [];

// Fetch the posts from the database that match the email
$conn = getConnection();
$stmt = $conn->prepare("SELECT post_id, role, email, content, created_at FROM posts WHERE email = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $email);  // Bind the email parameter
$stmt->execute();
$result = $stmt->get_result();

// Check if any posts are found
if ($result->num_rows > 0) {
    // Fetch all posts into the $posts array
    $posts = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $noContentMessage = "No content found.";  // Message if no posts exist
}

$stmt->close();
$conn->close();

// Include the appropriate navbar based on the role
if ($role === 'Student') {
    include('student_navbar.php');
} elseif ($role === 'Farmer') {
    include('farmer_navbar.php');
} elseif ($role === 'Consumer') {
    include('consumer_navbar.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-gray-100">

    <!-- Container for displaying posts -->
    <div class="container mx-auto my-8">

        <h1 class="text-3xl font-bold mb-4">My Posts</h1>

        <!-- Check if posts exist -->
        <?php if (!empty($posts)) : ?>
            <div class="space-y-4">
                <?php foreach ($posts as $post) : ?>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <p class="text-gray-800 mb-2"><?php echo htmlspecialchars($post['content']); ?></p>
                        <div class="text-sm text-gray-500">
                            <p><strong>Posted on:</strong> <?php echo htmlspecialchars($post['created_at']); ?></p>
                        </div>

                        <!-- Comment Button -->
                        <form method="POST" action="../controller/post_comment.php">
                            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                            <button type="submit" class="mt-4 bg-[#1f4e3e] text-white px-4 py-2 rounded-lg hover:bg-[#355b4e] focus:outline-none">
                                View Comments
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="text-gray-600"><?php echo isset($noContentMessage) ? htmlspecialchars($noContentMessage) : ''; ?></p>
        <?php endif; ?>

    </div>

</body>
</html>
