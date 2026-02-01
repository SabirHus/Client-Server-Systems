<?php
// Start a session so we can access the logged-in user's role
session_start();

// Include the class that handles database actions for facilities
require_once('Models/FacilityDataSet.php');


// Only managers should be able to delete data
if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'User') {
    // Regular users are not authorized to delete records
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

// Check if a facility ID was submitted
$id = $_POST['id'] ?? null;

if (!$id) {
    // If no ID is provided, return a client error
    http_response_code(400); // Bad Request
    echo "No facility ID provided";
    exit;
}

$dataset = new FacilityDataSet();

// Run the deletion query and return the result
if ($dataset->deleteFacility($id)) {
    echo "Facility deleted successfully!";
} else {
    // If something goes wrong in the database
    http_response_code(500); // Internal Server Error
    echo "Deletion failed.";
}
