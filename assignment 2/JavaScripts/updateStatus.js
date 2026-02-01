class StatusUpdater {
    constructor(dropdownClass, buttonClass, endpoint) {
        // Class name for all dropdowns used to select a new status
        this.dropdownClass = dropdownClass;

        // Class name for the update buttons
        this.buttonClass = buttonClass;

        // Server-side endpoint that processes the update
        this.endpoint = endpoint;

        // Setup event listener for update buttons
        this.attachListeners();
    }

    // Listens for clicks on any update-status buttons and sends the new status
    attachListeners() {
        document.addEventListener("click", (e) => {
            // Only handle clicks on elements with the update button class
            if (!e.target.classList.contains(this.buttonClass)) return;

            // Get the facility ID from the clicked button
            const id = e.target.dataset.id;

            // Find the corresponding dropdown for that facility
            const select = document.querySelector(`.${this.dropdownClass}[data-id='${id}']`);
            if (!select) return;

            // Grab the selected status from the dropdown
            const newStatus = select.value;

            // Send this status to the backend for update
            this.updateStatus(id, newStatus);
        });
    }

    // Handles sending the update request to the server
    updateStatus(id, status) {
        fetch(this.endpoint, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            // Send both facility ID and new status value
            body: `id=${encodeURIComponent(id)}&status=${encodeURIComponent(status)}`
        })
            .then(res => res.text()) // Parse server response as plain text
            .then(msg => {
                alert(msg); // Show confirmation or failure message

                if (typeof ecoMap?.loadMarkers === "function") {
                    ecoMap.loadMarkers(); // Refresh markers to reflect new status
                }
            })
            .catch(() => alert("Failed to update status.")); // Fallback error message
    }
}

// Wait until the page is fully loaded before attaching event handlers
document.addEventListener("DOMContentLoaded", () => {
    // Create an instance of the class using your existing DOM structure
    new StatusUpdater("status-dropdown", "update-status-btn", "updateStatus.php");
});