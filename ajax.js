// Wait for the DOM to be fully loaded before running the script.
document.addEventListener('DOMContentLoaded', () => {

    // Get the form and modal elements.
    const signupForm = document.getElementById('signupForm');
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    const modalMessage = document.getElementById('modalMessage');

    // Add an event listener to the form for when it is submitted.
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

            // Check if the network request was successful.
            if (!response.ok) {
                throw new Error('Network response was not ok');
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
});
