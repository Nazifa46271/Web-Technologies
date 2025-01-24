<?php
// Start the session
// session_start();

// Check if the logout parameter is set in the URL
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Destroy the session
    session_unset();
    session_destroy();

    // Redirect to login page
    header('Location: login.html');
    exit;
}

// Include the database model only once (adjust path)
include_once('../model/database.php'); // Adjust the relative path to 'model/database.php'

// Retrieve session variables
$email = $_SESSION['email'];
$role = $_SESSION['role'];
$userId = $_SESSION['user_id'];

// Fetch the student's name based on the userId
$studentName = getStudentNameById($userId);

if (!$studentName) {
    $studentName = "Guest";  // Default value if student is not found
}

// Define the menu items and their corresponding links
$menuItems = [
    ["name" => "Home", "link" => "student_dashboard.php"],
    ["name" => "Write a post", "link" => "write_post.php"],
    ["name" => "My Posts", "link" => "my_post.php"],
    ["name" => "My Profile", "link" => "student_profile.php"],
];
?>

<div class="navbar bg-[#1f4e3e] text-white px-8 ">
  <div class="navbar-start">
    <!-- <a class="btn btn-ghost text-xl">Welcome, <?php// echo htmlspecialchars($studentName); ?></a> -->
     <a href="" class="text-2xl font-bold">AgriConnect</a>
  </div>
  <div class="navbar-center hidden lg:flex">
    <ul class="menu menu-horizontal px-1">
      <?php
      // Loop through the $menuItems array for the horizontal navbar as well
      foreach ($menuItems as $menuItem) {
          echo "<li><a href='" . htmlspecialchars($menuItem['link']) . "'>" . htmlspecialchars($menuItem['name']) . "</a></li>";
      }
      ?>
    </ul>
  </div>
  <div class="navbar-end">
    <a class="btn" href="?logout=true">Logout</a>  <!-- Logout link with query string -->
  </div>
</div>
