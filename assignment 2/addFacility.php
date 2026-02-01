<?php
// Start the session if it's not already active (for login and user info)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load the database model for interacting with facility records
require_once('Models/FacilityDataSet.php');

// Load the login logic — handles session-based login checks
require_once('loginController.php');

// Create the database handler for facilities
$model = new FacilityDataSet();

// Prepare the $view object to pass data to the form view
$view = new stdClass();
$view->pageTitle = 'Add Facility';


// -------------------
// Handle form submission
// -------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // These are the minimum required fields from the form
    $required = ['title', 'description', 'town', 'postcode', 'lat', 'lng', 'status', 'category'];

    // Loop over all required fields to check if any are missing
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            // If any field is missing, stop and return an error
            http_response_code(400);
            echo "Missing field: $field";
            exit;
        }
    }

    // Prepare data array to be inserted into the database
    $data = [
        'title' => $_POST['title'],
        'category' => $_POST['category'],
        'description' => $_POST['description'],
        'houseNumber' => $_POST['houseNumber'] ?? '',
        'streetName' => $_POST['streetName'] ?? '',
        'county' => $_POST['county'] ?? '',
        'town' => $_POST['town'],
        'postcode' => $_POST['postcode'],
        'lng' => $_POST['lng'],
        'lat' => $_POST['lat'],
        'contributor' => $_SESSION['userID'] ?? 1,
        'status' => $_POST['status']
    ];

    if ($model->insertFacility($data)) {
        echo "Facility added successfully!";
    } else {
        http_response_code(500);
        echo "Failed to add facility.";
    }

    exit; // Prevent the form from showing again after a POST
}

// Load category and status options from DB for the form dropdowns
$view->categories = $model->fetchCategories();
$view->statuses = $model->fetchStatuses();

// Show the HTML form
require('Views/addFacility.phtml');
