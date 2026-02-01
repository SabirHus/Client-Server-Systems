class DeleteFacility {
    constructor(triggerClass, endpoint, onSuccess = null) {
        this.triggerClass = triggerClass;
        this.endpoint = endpoint;
        this.onSuccess = onSuccess;

        this.init(); // Start listening for delete events
    }

    /**
     * Attach a global click event listener that responds to elements with the delete trigger class.
     */
    init() {
        document.addEventListener("click", (e) => {
            // Check if the clicked element is a delete button
            if (e.target && e.target.classList.contains(this.triggerClass)) {
                const id = e.target.dataset.id; // Get the facility ID from the data attribute

                // Ask the user for confirmation before deleting
                if (!confirm("Are you sure you want to delete this facility?")) return;

                // Send the delete request
                this.sendDelete(id);
            }
        });
    }

    /**
     * Send a POST request to delete the facility by ID.
     * @param {string|number} id - The ID of the facility to delete.
     */
    sendDelete(id) {
        fetch(this.endpoint, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "id=" + encodeURIComponent(id) // Send the facility ID in the request body
        })
            .then(res => res.text()) // Parse response as plain text
            .then(msg => {
                alert(msg); // Show the result to the user

                // Call the success callback if provided
                if (typeof this.onSuccess === "function") this.onSuccess();
            })
            .catch(() => alert("Failed to delete")); // Handle any errors during the request
    }
}
