<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in and get the user_id
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$user_id = $_SESSION['user_id']; // Farmer ID

include("farmer_navbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Crop</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>


</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-5 text-gray-800 text-center">Add New Product</h2>
        <div class="divider"></div>
        <form action="../controller/farmer_addCrop.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Crop Name -->
            <div>
                <label for="crop_name" class="block text-gray-700 font-medium">Crop Name</label>
                <input type="text" id="crop_name" name="crop_name" required 
                       class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300" />
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-gray-700 font-medium">Description</label>
                <textarea id="description" name="description" required rows="4"
                          class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300"></textarea>
            </div>

            <!-- Quantity -->
            <div>
                <label for="quantity" class="block text-gray-700 font-medium">Quantity (kg)</label>
                <input type="number" id="quantity" name="quantity" required min="1" 
                       class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300" />
            </div>

            <!-- Available Quantity -->
            <div>
                <label for="available_quantity" class="block text-gray-700 font-medium">Available Quantity (kg)</label>
                <input type="number" id="available_quantity" name="available_quantity" required min="1" 
                       class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300" />
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-gray-700 font-medium">Price (per kg)</label>
                <input type="number" id="price" name="price" required min="1" 
                       class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300" />
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-gray-700 font-medium">Crop Image</label>
                <input type="file" id="image" name="image" required accept="image/*" 
                       class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300" />
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="bg-[#1f4e3e] text-white px-6 font-bold text-lg py-2 w-full rounded-md hover:bg-[#356051]">
                    Upload Product Info
                </button>
            </div>
        </form>
    </div>
</body>
</html>
