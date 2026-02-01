<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load model class to access facilities
require_once('Models/FacilityDataSet.php');

// Create view object to hold variables used in the template
$view = new stdClass();
$view->pageTitle = 'Homepage';
$view->loginError = false;

// Load login logic to check session & credentials
require_once('loginController.php');

// Instantiate Facility dataset object to run queries
$ecoFacilities = new FacilityDataSet();

// Handle pagination values
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// If a search term is provided, override paginated fetch
$searchTerm = $_GET['search'] ?? null;
$facilities = [];
$showReset = false;

if ($searchTerm) {
    $facilities = $ecoFacilities->searchFacilities($searchTerm);
    $total = count($facilities); // simple count of search result
    $showReset = true;
} else {
    $facilities = $ecoFacilities->fetchPaginatedFacilities($limit, $offset);
    $total = $ecoFacilities->countFacilities();
}

$totalPages = ceil($total / $limit);

// Prepare mapping of user IDs to usernames
$userMap = [];
foreach ($ecoFacilities->getAllUsers() as $u) {
    $userMap[$u['id']] = $u['username'];
}

// Prepare mapping of status IDs to status strings
$statusMap = $ecoFacilities->getAllStatuses();

$view->FacilityDataSet = $ecoFacilities->fetchAllFacilities();

// Provide result count message
if (count($view->FacilityDataSet) == 0) {
    $view->dbMessage = "No results";
} else {
    $view->dbMessage = count($view->FacilityDataSet) . " result(s)";
}

// Load main view template
require_once('Views/index.phtml');