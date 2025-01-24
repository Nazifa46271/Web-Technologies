<?php
// Start the session to access session variables
session_start();

// Check if the email is set in the session, and store it in a variable
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Guest';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Invalid ID';

// Handle logout action
if (isset($_GET['logout'])) {
    // Clear the session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to login page
    header("Location: ../view/login.html");
    exit;
}
include("farmer_navbar.php");


?>
<!DOCTYPE html>
<html>
<head>
    <title>Farmer Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
  <div class="carousel w-full">
    <div id="slide1" class="carousel-item relative w-full max-h-screen ">
      <img
        src="../asset/images/agriBanner.jpg"
        class="w-full max-h-screen  object-cover" />
      <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
        <a href="#slide3" class="btn btn-circle">❮</a>
        <a href="#slide2" class="btn btn-circle">❯</a>
      </div>
    </div>

    <div id="slide2" class="carousel-item relative w-full max-h-screen">
      <img
        src="../asset/images/agriBanner2.jpg"
        class="w-full object-cover" />
      <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
        <a href="#slide1" class="btn btn-circle">❮</a>
        <a href="#slide3" class="btn btn-circle">❯</a>
      </div>
    </div>
    <div id="slide3" class="carousel-item relative w-full max-h-screen">
      <img
        src="../asset/images/agriBanner3.jpg"
        class="w-full  object-cover" />
      <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
        <a href="#slide2" class="btn btn-circle">❮</a>
        <a href="#slide1" class="btn btn-circle">❯</a>
      </div>
    </div>
  </div>

  <div class="bg-[#254f41] h-96">
    <div class='p-12  flex justify-center text-center'>
      <h1 class="font-bold text-5xl w-3/5 text-white text-center ">Add your fresh food to our basket now and make your first product entry!</h1>  
    </div>
  </div>
</body>
</html>
