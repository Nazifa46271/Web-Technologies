<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || !isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.html');
    exit;
}

// Retrieve session variables
$email = $_SESSION['email'];
$role = $_SESSION['role'];
$userId = $_SESSION['user_id'];

// Include the database model and functions
include('../model/database.php');

// Include the appropriate navbar based on the role
if ($role === 'Student') {
    include('student_navbar.php');
} elseif ($role === 'Farmer') {
    include('farmer_navbar.php');
} elseif ($role === 'Consumer') {
    include('consumer_navbar.php');
}

// Fetch all posts from the database
$conn = getConnection();
$stmt = $conn->prepare("SELECT post_id, role, email, content, created_at FROM posts ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

// Helper function to get the poster's name based on role
function getPosterNameByRoleAndEmail($role, $email) {
    if ($role === 'Student') {
        return getStudentNameByEmail($email);
    } elseif ($role === 'Farmer') {
        return getFarmerNameByEmail($email);
    } elseif ($role === 'Consumer') {
        return getConsumerNameByEmail($email);
    }
    return "Unknown User"; // Default value for unknown role
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Posts</title>

    <!-- Add Tailwind CSS and DaisyUI CDN links here -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="container mx-auto my-8">
        <h2 class="text-xl font-semibold mt-8 mb-4">All Posts</h2>
        
        <div class="space-y-4">
            <?php if (!empty($posts)) : ?>
                <?php foreach ($posts as $post) : 
                    // Get the poster's name dynamically based on the role
                    $posterName = getPosterNameByRoleAndEmail($post['role'], $post['email']);
                ?>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <p class="text-gray-800 mb-2"><?php echo htmlspecialchars($post['content']); ?></p>
                        <div class="text-sm text-gray-500">
                            <p><strong>Posted by:</strong> <?php echo htmlspecialchars($posterName); ?> (<?php echo htmlspecialchars($post['role']); ?>)</p>
                            <p><strong>Posted on:</strong> <?php echo htmlspecialchars($post['created_at']); ?></p>
                        </div>
                        <!-- Comment Button Form -->
                        <form method="POST" action="../controller/post_comment.php">
                            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                            <div class="mt-4">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none">
                                    Comment
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-gray-600">No posts available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
