$(document).ready(function () {
    $('#paymentForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        // Send an AJAX POST request
        $.ajax({
            url: '../controller/confirm_purchase_controller.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (response) {
                if (response.redirect) {
                    // Redirect to the appropriate page
                    window.location.href = response.redirect;
                } else if (response.message) {
                    // Display any error message
                    $('#responseMessage').text(response.message);
                }
            },
            error: function (xhr, status, error) {
                $('#responseMessage').text('Error: ' + error);
            }
        });
    });
});
