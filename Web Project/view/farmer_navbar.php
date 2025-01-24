<?php
// Start the session

//  session_start();

// Check if the logout query string is set
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
   
    session_unset();

    session_destroy();

    header('Location: login.html');
    exit();
}

$menuItems = [
    ["name" => "Dashboard", "link" => "../view/farmer_menu.php"],
    // ["name" => "Tutorial Upload", "link" => "upload_video.html"],
    ["name" => "post", "link" => "../view/show_post.php"],
    ["name" => "Write a post", "link" => "../view/write_post.php"],
    ["name" => "My Posts", "link" => "../view/my_post.php"],
    ["name" => "My Profile", "link" => "../view/farmer_profile.php"],
    ["name" => "Add Product", "link" => "../view/farmer_addProduct.php"],
    ["name" => "View Product", "link" => "../controller/farmer_view_crop.php"],
    ["name" => "Weather", "link" => "../view/weather.php"],
    ["name" => "Account", "link" => "../view/farmer_show_account.php"]
    // ["name" => "View Product", "link" => "../controller/farmer_view_crop.php"],
];
?>

<div class="navbar bg-[#1f4e3e] px-8">
  <div class="navbar-start">
     <a href="#" class="text-bold text-white text-2xl">AgriConnect</a>
  </div>
  <div class="navbar-center text-white text-3xl hidden lg:flex">
    <ul class="menu menu-horizontal px-1 ">
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
