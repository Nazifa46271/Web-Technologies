<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    // Redirect to login page if the user is not logged in
    header('Location: login.html');
    exit;
}

// Get the user's ID from the session
$userId = $_SESSION['user_id'];

// Include the database connection
include_once('../model/database.php');

// Fetch student data from the database
$conn = getConnection();
$stmt = $conn->prepare("SELECT * FROM student WHERE id = ?");
$stmt->bind_param("i", $userId); // Bind the user ID as an integer
$stmt->execute();
$result = $stmt->get_result();

// Check if the student exists
if ($result->num_rows > 0) {
    // Fetch the student data into an associative array
    $student = $result->fetch_assoc();
} else {
    // If no student is found with this ID, redirect or display an error
    echo "Student data not found.";
    exit;
}

$stmt->close();
$conn->close();

// Update student profile logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Get form data and update the student details
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $mobile = $_POST['mobile'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];

    // Handle profile picture upload if provided
    $profileImage = $student['profile_image']; // Default to current image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        // Get file details
        $imageTmpName = $_FILES['profile_image']['tmp_name'];
        $imageName = $_FILES['profile_image']['name'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageNewName = uniqid() . '.' . $imageExtension; // Generate a unique name for the image

        // Define the upload path
        $uploadDir = '../asset/images/';
        $uploadPath = $uploadDir . $imageNewName;

        // Move the uploaded image to the images directory
        if (move_uploaded_file($imageTmpName, $uploadPath)) {
            $profileImage = $imageNewName; // Update the image filename
        }
    }

    // Update the database with the new information
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE student SET first_name = ?, last_name = ?, mobile = ?, country = ?, address = ?, dob = ?, profile_image = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $firstName, $lastName, $mobile, $country, $address, $dob, $profileImage, $userId);
    $stmt->execute();

    // Close connection
    $stmt->close();
    $conn->close();

    // Redirect to refresh the page and show updated profile
    header("Location: student_profile.php");
    exit;
}

// Include the navbar (you can use your existing navbar code here)
include('student_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-gray-100">

    <!-- Container for displaying student profile -->
    <div class="container mx-auto my-8">

        <h1 class="text-3xl font-bold mb-4">My Profile</h1>

        <!-- Profile details -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center mb-4">
                <!-- Display Profile Image -->
                <img src="../asset/images/<?php echo htmlspecialchars($student['profile_image']); ?>" alt="Profile Image" class="w-24 h-24 rounded-full object-cover mr-4">
                <div>
                    <h2 class="text-2xl font-semibold"><?php echo htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']); ?></h2>
                    <p class="text-gray-500"><?php echo htmlspecialchars($student['role']); ?></p>
                </div>
            </div>

            <div class="text-gray-800">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Mobile:</strong> <?php echo htmlspecialchars($student['mobile']); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($student['country']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student['dob']); ?></p>
            </div>
            
            <!-- Edit Button -->
            <button id="edit-btn" class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4 hover:bg-blue-600 focus:outline-none">Edit</button>
        </div>

        <!-- Edit Profile Form -->
        <form id="edit-form" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg mt-6 hidden">
            <h2 class="text-2xl font-semibold mb-4">Edit Profile</h2>
            <div class="mb-4">
                <label for="first_name" class="block text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="mobile" class="block text-gray-700">Mobile</label>
                <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($student['mobile']); ?>" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="country" class="block text-gray-700">Country</label>
                <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($student['country']); ?>" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700">Address</label>
                <textarea id="address" name="address" class="w-full p-2 border rounded-lg"><?php echo htmlspecialchars($student['address']); ?></textarea>
            </div>
            <div class="mb-4">
                <label for="dob" class="block text-gray-700">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($student['dob']); ?>" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="profile_image" class="block text-gray-700">Profile Image (Optional)</label>
                <input type="file" id="profile_image" name="profile_image" class="w-full p-2 border rounded-lg">
            </div>
            <button type="submit" name="update_profile" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none">Update Profile</button>
        </form>

    </div>

    <script>
        // Toggle edit form visibility
        const editBtn = document.getElementById("edit-btn");
        const editForm = document.getElementById("edit-form");

        editBtn.addEventListener("click", () => {
            editForm.classList.toggle("hidden");
            editBtn.textContent = editForm.classList.contains("hidden") ? "Edit" : "Cancel Edit";
        });
    </script>

</body>
</html>
