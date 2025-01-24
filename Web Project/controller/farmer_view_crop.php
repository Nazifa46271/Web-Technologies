<?php
// Start session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

include("../view/farmer_navbar.php");

// Create connection to the database
$conn = mysqli_connect('127.0.0.1', 'root', '', 'agriculture');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the crops added by the logged-in farmer
$farmer_id = $_SESSION['user_id'];
$sql = "SELECT * FROM crop WHERE farmer_id = $farmer_id";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Crops</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto">
        <h2 class="text-2xl font-bold my-6 text-center text-black ">Product Details</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="overflow-x-auto">
                <table class="table w-full border border-gray-300">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th>Crop Name</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Available Quantity</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        // Set the class based on a condition
                        $rowClass = '';
                        if ($row['available_quantity'] < 10) {
                            $rowClass = 'bg-red-100'; // Light red for low quantity
                        } elseif ($row['available_quantity'] >= 10 && $row['available_quantity'] <= 50) {
                            $rowClass = 'bg-yellow-100'; // Light yellow for medium quantity
                        } else {
                            $rowClass = 'bg-green-100'; // Light green for high quantity
                        }
                        ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td class="py-2 px-4"><?php echo htmlspecialchars($row['crop_name']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($row['description']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($row['available_quantity']); ?></td>
                            <td class="py-2 px-4">BDT <?php echo htmlspecialchars($row['price']); ?></td>
                            <td class="py-2 px-4">
                                <img src="../asset/images/<?php echo htmlspecialchars($row['image']); ?>" alt="Crop Image" class="w-24 h-24 object-cover border rounded-lg">
                            </td>
                            <td class="py-2 px-4">
                                <button class="btn btn-primary px-4 py-2" onclick="openModal(<?php echo htmlspecialchars($row['crop_id']); ?>, '<?php echo htmlspecialchars($row['crop_name']); ?>', '<?php echo htmlspecialchars($row['description']); ?>', <?php echo htmlspecialchars($row['quantity']); ?>, <?php echo htmlspecialchars($row['available_quantity']); ?>, <?php echo htmlspecialchars($row['price']); ?>)">Edit</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-gray-600 mt-6">
                <p>You have not added any crops yet.</p>
            </div>
        <?php endif; ?>

    </div>

    <!-- Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
            <h3 class="text-xl font-bold mb-4">Edit Crop</h3>
            <form id="editForm">
                <input type="hidden" id="crop_id" name="crop_id">
                <div class="mb-4">
                    <label for="crop_name" class="block font-medium">Crop Name</label>
                    <input type="text" id="crop_name" name="crop_name" class="input input-bordered w-full">
                </div>
                <div class="mb-4">
                    <label for="description" class="block font-medium">Description</label>
                    <textarea id="description" name="description" class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block font-medium">Quantity</label>
                    <input type="number" id="quantity" name="quantity" class="input input-bordered w-full">
                </div>
                <div class="mb-4">
                    <label for="available_quantity" class="block font-medium">Available Quantity</label>
                    <input type="number" id="available_quantity" name="available_quantity" class="input input-bordered w-full">
                </div>
                <div class="mb-4">
                    <label for="price" class="block font-medium">Price</label>
                    <input type="text" id="price" name="price" class="input input-bordered w-full">
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(cropId, cropName, description, quantity, availableQuantity, price) {
            document.getElementById("crop_id").value = cropId;
            document.getElementById("crop_name").value = cropName;
            document.getElementById("description").value = description;
            document.getElementById("quantity").value = quantity;
            document.getElementById("available_quantity").value = availableQuantity;
            document.getElementById("price").value = price;
            document.getElementById("editModal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("editModal").classList.add("hidden");
        }

        $("#editForm").on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                url: "../controller/farmer_update_crop.php",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert("Crop updated successfully!");
                    location.reload();
                },
                error: function() {
                    alert("An error occurred while updating the crop.");
                }
            });
        });
    </script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
