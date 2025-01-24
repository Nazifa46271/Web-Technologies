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

// Get the user's role and email from the session
$email = $_SESSION['email'];
$role = $_SESSION['role'];

// Initialize a variable to hold the alert message
$alertMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the content from the form
    $content = $_POST['content'];

    // Validate the input
    if (!empty(trim($content))) {
        // Save the post to the database
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO posts (role, email, content) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $role, $email, $content);

        if ($stmt->execute()) {
            $alertMessage = "Post saved successfully!";
        } else {
            $alertMessage = "Error saving post: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        $alertMessage = "Content cannot be empty!";
    }
}

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
    <title>Write Post</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script>
        // JavaScript to automatically hide the alert after 5 seconds
        function hideAlert() {
            const alertBox = document.getElementById('alert-box');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 300); // Remove from DOM after fade-out
                }, 3000); 
            }
        }

        document.addEventListener('DOMContentLoaded', hideAlert);
    </script>
</head>
<body class="bg-gray-100">
     <div class="container mx-auto my-8 flex justify-center">
        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-xl">
            <h1 class="text-2xl font-bold mb-4">Write a Post</h1>

            <!-- Display alert if message is set -->
            <?php if (!empty($alertMessage)) : ?>
                <div id="alert-box" class="mb-4 p-4 text-blue-800 bg-blue-100 border border-blue-300 rounded-lg transition-opacity">
                    <?php echo htmlspecialchars($alertMessage); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <textarea 
                        name="content" 
                        rows="5" 
                        class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Write your content here..."
                        required></textarea>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-[#1f4e3e] text-white py-2 rounded-lg hover:bg-[#355b4e] transition">
                    Submit
                </button>
            </form>

            <div class="mt-4">
                <a href="student_dashboard.php" class="text-blue-500 hover:underline">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
