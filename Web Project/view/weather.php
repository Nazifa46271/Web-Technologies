<?php include ("farmer_navbar.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Weather Update</title>
    <link rel="stylesheet" type="text/css" href="../asset/weather.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="min-h-screen mt-8 flex flex-col">

    <!-- Form Section -->
    <div class="flex flex-1 items-center justify-center w-full min-h-screen">
    <form id="weather-form" class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg ">
        <fieldset class="p-3"> 
            <legend class="text-2xl font-bold text-blue-600 mb-6 text-center">🌤️ Weather Update</legend>
            <div class="mb-6">
                <label for="city" class="block text-gray-700 font-semibold mb-2">Enter City:</label>
                <input 
                    type="text" 
                    id="city" 
                    name="city" 
                    required 
                    placeholder="e.g., Dhaka"
                    class="border border-gray-300 rounded-lg w-full p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>
            <button 
                type="submit" 
                class="w-full bg-blue-500 text-white py-3 px-6 rounded-lg font-medium text-lg hover:bg-blue-600 transition duration-200"
            >
                Get Weather
            </button>
            <div id="weather-info" class="mt-6 text-gray-700 text-center"></div>
            <button 
                type="button" 
                onclick="window.location.href='../view/farmer_menu.php';" 
                class="mt-6 w-full bg-gray-500 text-white py-3 px-6 rounded-lg font-medium text-lg hover:bg-gray-600 transition duration-200"
            >
                Back
            </button>
        </fieldset>
    </form>
</div>


    <script src="../asset/weather.js"></script>
</body>
</html>
