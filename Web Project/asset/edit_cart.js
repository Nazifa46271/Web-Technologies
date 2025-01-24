$(document).ready(function () {
    // Handle quantity change for cart update
    $('.update-quantity').click(function () {
        var button = $(this);
        var cartId = button.data('cart-id');
        var cropId = button.data('crop-id');
        var availableQuantity = button.data('available-quantity');
        var quantityInput = $('#quantity-' + cartId);
        var currentQuantity = parseInt(quantityInput.val(), 10);
        var change = button.data('change');
        var newQuantity = currentQuantity + change;

        // Check if the new quantity exceeds available quantity
        if (newQuantity < 1) {
            // Prevent negative quantity
            return;
        } else if (newQuantity > availableQuantity) {
            // Display error message if quantity exceeds available stock
            $('#message').text('Quantity exceeds available stock.');
            return;
        }

        // Update quantity
        quantityInput.val(newQuantity);

        // Make AJAX request to update the cart in the database
        $.ajax({
            url: '../controller/edit_cart.php',
            type: 'POST',
            dataType: 'json',
            data: {
                cart_id: cartId,
                crop_id: cropId,
                quantity: newQuantity
            },
            success: function (response) {
                if (response.success) {
                    // Update total price in the UI
                    $('#total-price-' + cartId).text('$' + response.total_price.toFixed(2));

                    // Update the hidden total price input field for the "Buy" button
                    $('#total-price-input-' + cartId).val(response.total_price.toFixed(2));

                    // Optionally display success message
                    $('#message').text('Cart updated successfully!');
                } else {
                    $('#message').text(response.error);
                }
            },
            error: function () {
                $('#message').text('An error occurred while updating the cart.');
            }
        });
    });

    // Ensure the total price and quantity are updated in the hidden fields before form submission
    $('form').submit(function () {
        // Get cart ID from hidden field
        var cartId = $(this).find('input[name="cart_id"]').val();

        // Get the latest price from the UI (updated by the quantity change)
        var updatedPrice = $('#total-price-' + cartId).text().replace('$', '');

        // Get the updated quantity from the input field
        var updatedQuantity = $('#quantity-' + cartId).val();

        // Update the hidden total price and quantity fields
        $(this).find('input[name="total_price"]').val(updatedPrice);
        $(this).find('input[name="quantity"]').val(updatedQuantity);
    });
});
