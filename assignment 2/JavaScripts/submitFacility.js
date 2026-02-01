class FormHandler {
    constructor(formId, endpoint, messageId) {
        this.form = document.getElementById(formId);       // Get the form element by ID
        this.messageBox = document.getElementById(messageId); // Get the message box element
        this.endpoint = endpoint;                          // URL to submit form data to

        // Only initialize if form element is found
        if (this.form) {
            this.init();
        }
    }

    /**
     * Bind the submit event to the form and prevent default browser behavior.
     */
    init() {
        this.form.addEventListener("submit", (e) => {
            e.preventDefault(); // Prevent the page from reloading
            this.submitForm();  // Trigger AJAX form submission
        });
    }

    /**
     * Gather form data and send it via XMLHttpRequest to the server.
     */
    submitForm() {
        const formData = new FormData(this.form);     // Collect form fields and values
        const xhr = new XMLHttpRequest();             // Create a new AJAX request
        xhr.open("POST", this.endpoint, true);        // Prepare POST request to the endpoint

        xhr.onload = () => {
            const success = xhr.status === 200;       // Check if the response is OK
            const className = success ? "success" : "danger"; // Bootstrap alert class
            this.messageBox.innerHTML = `<div class="alert alert-${className}">${xhr.responseText}</div>`; // Show response

            if (success) this.form.reset();           // Reset form if it was successful
        };

        xhr.send(formData); // Send the collected form data
    }
}
