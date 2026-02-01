<?php
// Include the database interaction class for facilities
require_once('Models/FacilityDataSet.php');

// This script returns an XML response
header("Content-Type: text/xml");

// Read the user's search input from the URL trim it and make it lowercase for easier comparison
$searchTerm = strtolower(trim($_GET['q'] ?? ""));

// Start an empty <facilities> XML document to build our response
$xmlOutput = new SimpleXMLElement("<facilities></facilities>");

// Only run a search if the user typed something
if ($searchTerm !== "") {
    // Create the model to access database methods
    $model = new FacilityDataSet();

    // Call the search method which looks for matches across several fields (title, category, town, etc.)
    $results = $model->searchFacilities($searchTerm);

    // Loop through all matching results and convert each one into a <facility> block inside our XML
    foreach ($results as $facilityData) {
        $facility = $xmlOutput->addChild("facility");

        // Add each property as a child element inside <facility>
        $facility->addChild("id", $facilityData['id']);
        $facility->addChild("title", htmlspecialchars($facilityData['title']));
        $facility->addChild("category", htmlspecialchars($facilityData['category']));
        $facility->addChild("town", htmlspecialchars($facilityData['town']));
        $facility->addChild("description", htmlspecialchars($facilityData['description']));
    }
}

// Convert the XML object into a string and send it to the browser
echo $xmlOutput->asXML();
