// Wait for the DOM to be fully loaded before running the script.
document.addEventListener('DOMContentLoaded', () => {

    // Get the form and modal elements.
    const signupForm = document.getElementById('signupForm');
    const loginForm = document.getElementById('loginForm');
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    const modalMessage = document.getElementById('modalMessage');

    // Add an event listener to the signup form for when it is submitted.
    if (signupForm) {
        signupForm.addEventListener('submit', async (event) => {
            // Prevent the default form submission to handle it with AJAX.
            event.preventDefault();

            // Check if the form is valid before proceeding.
            if (!signupForm.checkValidity()) {
                signupForm.classList.add('was-validated');
                return;
            }

            // Create a FormData object from the form to easily get all input values.
            const formData = new FormData(signupForm);

            try {
                // Use the Fetch API to send the form data to the PHP script.
                const response = await fetch('signup_process.php', {
                    method: 'POST',
                    body: formData,
                });

                // Check for specific server-side error statuses before parsing the response
                if (!response.ok) {
                    if (response.status === 400) {
                        window.location.href = '400.php';
                    } else if (response.status === 403) {
                        window.location.href = '403.php';
                    } else if (response.status === 404) {
                        window.location.href = '404.php';
                    } else if (response.status === 500) {
                        window.location.href = '500.php';
                    } else {
                        throw new Error('Network response was not ok');
                    }
                }

                // Parse the JSON response from the PHP script.
                const data = await response.json();

                // Update the modal message based on the response.
                modalMessage.textContent = data.message;
                
                // Show the modal.
                statusModal.show();

                // If the signup was successful, reset the form.
                if (data.success) {
                    signupForm.reset();
                    signupForm.classList.remove('was-validated');
                }

            } catch (error) {
                console.error('Error:', error);
                modalMessage.textContent = 'An error occurred. Please try again.';
                statusModal.show();
            }
        });
    }

    // Add an event listener to the login form for when it is submitted.
    if (loginForm) {
        loginForm.addEventListener('submit', async (event) => {
            // Prevent the default form submission to handle it with AJAX.
            event.preventDefault();

            // Check if the form is valid before proceeding.
            if (!loginForm.checkValidity()) {
                loginForm.classList.add('was-validated');
                return;
            }

            // Create a FormData object from the form.
            const formData = new FormData(loginForm);

            try {
                // Use the Fetch API to send the form data to the login script.
                const response = await fetch('login_process.php', {
                    method: 'POST',
                    body: formData,
                });

                // Check for specific server-side error statuses before parsing the response
                if (!response.ok) {
                    if (response.status === 400) {
                        window.location.href = '400.php';
                    } else if (response.status === 403) {
                        window.location.href = '403.php';
                    } else if (response.status === 404) {
                        window.location.href = '404.php';
                    } else if (response.status === 500) {
                        window.location.href = '500.php';
                    } else {
                        throw new Error('Network response was not ok');
                    }
                }

                const data = await response.json();

                modalMessage.textContent = data.message;
                statusModal.show();

                // If login is successful, you might want to redirect the user
                if (data.success) {
                    // Redirect to a dashboard or a protected page after a brief delay
                    setTimeout(() => {
                         window.location.href = 'dashboard.php'; // Example redirect
                    }, 1000); 
                }

            } catch (error) {
                console.error('Error:', error);
                modalMessage.textContent = 'An error occurred. Please try again.';
                statusModal.show();
            }
        });
    }
});
