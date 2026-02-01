<?php
session_start(); // Start or resume the session to check user role

require_once('Models/FacilityDataSet.php'); // Include access to database methods

// Only allow access if logged-in user is a Manager
if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'Manager') {
    http_response_code(403);
    echo "Unauthorized";     // Message for unauthorized users
    exit;
}

// Get the facility ID and new status value from the AJAX POST request
$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

// Make sure both values are present before proceeding
if (!$id || !$status) {
    http_response_code(400); // Bad request
    echo "Missing data.";
    exit;
}

// Use the model to update the facility's status in the database
$model = new FacilityDataSet();
if ($model->updateStatus($id, $status)) {
    echo "Status updated successfully!";
} else {
    http_response_code(500); // Server error if update fails
    echo "Update failed.";
}