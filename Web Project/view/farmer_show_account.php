<?php
// Start session
session_start();

// Check if farmer is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'You must be logged in to view your account details.']));
}

// Database connection
$conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

include("farmer_navbar.php");


// Get the farmer ID from session
$farmer_id = $_SESSION['user_id'];

// Query to calculate total sales and other details
$sql = "
    SELECT 
        c.crop_name,
        SUM(cp.amount_bought) AS total_quantity_sold,
        SUM(cp.amount_bought * c.price) AS total_sales,
        COUNT(DISTINCT cp.crop_id) AS unique_crops_sold
    FROM 
        consumer_purchase cp
    INNER JOIN 
        crop c ON cp.crop_id = c.crop_id
    WHERE 
        c.farmer_id = $farmer_id
    GROUP BY 
        c.crop_name
";

// Execute the query
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Prepare dashboard data
$sales_data = [];
$total_sales = 0;
$total_crops_sold = 0;
$total_unique_crops = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $sales_data[] = [
        'crop_name' => $row['crop_name'],
        'total_quantity_sold' => $row['total_quantity_sold'],
        'total_sales' => $row['total_sales']
    ];
    $total_sales += $row['total_sales'];
    $total_crops_sold += $row['total_quantity_sold'];
    $total_unique_crops++;
}

// Close connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Account Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
   

</head>
<body class="bg-gray-100">
    <div class="container mx-auto my-8 p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Farmer Account Dashboard</h1>

        <div class="mb-6">
            <p class="text-lg font-semibold">Total Sales: <span class="text-green-600">$<?php echo number_format($total_sales, 2); ?></span></p>
            <p class="text-lg font-semibold">Total Crops Sold: <span class="text-blue-600"><?php echo $total_crops_sold; ?> units</span></p>
            <p class="text-lg font-semibold">Unique Crops Sold: <span class="text-purple-600"><?php echo $total_unique_crops; ?></span></p>
        </div>

        <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Crop Name</th>
                    <th class="border border-gray-300 px-4 py-2">Total Quantity Sold</th>
                    <th class="border border-gray-300 px-4 py-2">Total Sales ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales_data as $data): ?>
                <tr>
                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($data['crop_name']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $data['total_quantity_sold']; ?></td>
                    <td class="border border-gray-300 px-4 py-2">$<?php echo number_format($data['total_sales'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
