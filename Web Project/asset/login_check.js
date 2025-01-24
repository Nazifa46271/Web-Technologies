document.getElementById('submit-btn').addEventListener('click', function (event) {
    event.preventDefault();  // Prevent the default form submission action

    // Creating an object to hold the form data
    const formData = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
    };

    // Basic email validation before sending the request
    if (!validateEmail(formData.email)) {
        alert("Invalid email address.");
        return;  
    }

    if (formData.password.length < 1) {
        alert("Password is required.");
        return; 
    }

    fetch('../controller/logincheck.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json' 
        },
        body: JSON.stringify(formData)  
    })
    .then(function(response) {
        return response.json(); 
    })
    .then(function(responseData) {
        //if the login is successfull it will redirect based on the role
        if (responseData.success) {
            alert("Login successful!");
            window.location.href = responseData.redirectUrl;  
        } else {
            alert(responseData.error);  
        }
    })
    .catch(function(err) {
        console.error("Error during login:", err);  
        alert("An unexpected error occurred.");  
    });
});

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);  
}
