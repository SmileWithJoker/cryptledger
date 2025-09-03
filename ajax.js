// Wait for the DOM to be fully loaded before running the script.
document.addEventListener('DOMContentLoaded', () => {

    // Get the forms and modal elements.
    const signupForm = document.getElementById('signupForm');
    const loginForm = document.getElementById('loginForm');
    const requestOtpForm = document.getElementById('requestOtpForm');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    const modalMessage = document.getElementById('modalMessage');

    // Function to handle form submissions via AJAX
    async function handleFormSubmit(event, form, processorFile) {
        // Prevent the default form submission to handle it with AJAX.
        event.preventDefault();

        // Check if the form is valid before proceeding.
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Create a FormData object from the form to easily get all input values.
        const formData = new FormData(form);

        // Special handling for the password reset form to add the email.
        if (form.id === 'resetPasswordForm') {
            formData.append('email', resetPasswordForm.email);
        }

        try {
            // Use the Fetch API to send the form data to the PHP script.
            const response = await fetch(processorFile, {
                method: 'POST',
                body: formData,
            });

            // Check if the network request was successful.
            if (!response.ok) {
                // If it's a known error status, redirect to the custom error page.
                if (response.status === 400 || response.status === 403 || response.status === 404 || response.status === 500) {
                    window.location.href = `/${response.status}.php`;
                    return;
                }
                throw new Error('Network response was not ok');
            }

            const responseText = await response.text();
            let data;
            try {
                // Parse the JSON response from the PHP script.
                data = JSON.parse(responseText);
            } catch (jsonError) {
                console.error("JSON parsing error:", jsonError);
                console.error("Server responded with:", responseText);
                modalMessage.textContent = 'A server error occurred. Please try again later.';
                statusModal.show();
                return;
            }

            // Update the modal message based on the response.
            modalMessage.textContent = data.message;
            
            // Show the modal.
            statusModal.show();

            // If the process was successful, reset the form.
            if (data.success) {
                form.reset();
                form.classList.remove('was-validated');

                // Special handling for the password reset workflow
                if (processorFile === 'forget-password.php' && form.id === 'requestOtpForm') {
                    // On successful OTP request, hide the first form and show the second
                    document.getElementById('requestOtpForm').style.display = 'none';
                    document.getElementById('resetPasswordForm').style.display = 'block';
                    // Pass the email to the second form
                    document.getElementById('resetPasswordForm').email = document.getElementById('InputEmail').value;
                }
            }

        } catch (error) {
            console.error('Error:', error);
            modalMessage.textContent = 'An error occurred. Please try again.';
            statusModal.show();
        }
    }

    // Add event listeners to each form if it exists on the page.
    if (signupForm) {
        signupForm.addEventListener('submit', (event) => handleFormSubmit(event, signupForm, 'signup.php'));
    }

    if (loginForm) {
        loginForm.addEventListener('submit', (event) => handleFormSubmit(event, loginForm, 'login.php'));
    }

    if (requestOtpForm) {
        requestOtpForm.addEventListener('submit', (event) => {
            handleFormSubmit(event, requestOtpForm, 'forget-password.php');
        });
    }

    if (resetPasswordForm) {
        resetPasswordForm.addEventListener('submit', (event) => {
            handleFormSubmit(event, resetPasswordForm, 'forget-password.php');
        });
    }
});
