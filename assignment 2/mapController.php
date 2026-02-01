<?php
// Load the class responsible for database access to ecoFacilities
require_once("Models/FacilityDataSet.php");

// Tell the browser the response is JSON
header('Content-Type: application/json');

// Instantiate the model to interact with the database
$model = new FacilityDataSet();

// Retrieve all facilities including status joined via SQL
$facilities = $model->getAllFacilities();

// Return the dataset as a JSON-encoded string
echo json_encode($facilities);
