<?php

require_once ('Database.php');         // Connects to the shared PDO instance
require_once('FacilityData.php');     // Facility model to represent each facility row as an object

class FacilityDataSet
{
    protected $_dbHandle, $_dbInstance;

    // Constructor: Set up the database connection using the Singleton Database class
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();              // Get shared DB instance
        $this->_dbHandle = $this->_dbInstance->getDbConnection();  // Get the PDO handle
    }

    /**
     * Fetch all facilities as an array of FacilityData objects.
     * Used where full object properties are needed
     */
    public function fetchAllFacilities()
    {
        $sqlQuery = 'SELECT * FROM ecoFacilities';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FacilityData($row); // Wrap each row in a FacilityData object
        }
        return $dataSet;
    }

    /**
     * Search for facilities using keyword filtering on various fields.
     * Used by the live search suggestion and results display.
     */
    public function searchFacilities(string $query): array {
        $sql = "SELECT * FROM ecoFacilities 
                WHERE title LIKE :q 
                   OR category LIKE :q 
                   OR description LIKE :q 
                   OR streetName LIKE :q 
                   OR county LIKE :q 
                   OR town LIKE :q 
                   OR postcode LIKE :q 
                ORDER BY title ASC";

        $statement = $this->_dbHandle->prepare($sql);
        $statement->bindValue(':q', '%' . $query . '%');
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all facility records joined with status names.
     * Used for rendering map markers with human-readable status.
     */
    public function getAllFacilities(): array {
        $statement = $this->_dbHandle->query("
            SELECT f.id, f.title, f.description, f.town, f.lat, f.lng, s.statusComment AS status 
            FROM ecoFacilities f 
            JOIN ecoFacilityStatus s ON f.status = s.id
        ");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch all users to use as a contributor lookup map
     */
    public function getAllUsers(): array {
        $statement = $this->_dbHandle->query("SELECT id, username FROM ecoUser");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return all statuses as an associative map
     */
    public function getAllStatuses(): array {
        $statement = $this->_dbHandle->query("SELECT id, statusComment FROM ecoFacilityStatus");

        $statusMap = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $statusMap[$row['id']] = $row['statusComment'];
        }

        return $statusMap;
    }

    /**
     * Insert a new facility into the database.
     * Used when managers add new records from the form.
     */
    public function insertFacility(array $data): bool {
        $statement = $this->_dbHandle->prepare("
            INSERT INTO ecoFacilities 
            (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor, status) 
            VALUES 
            (:title, :category, :description, :houseNumber, :streetName, :county, :town, :postcode, :lng, :lat, :contributor, :status)
        ");

        return $statement->execute([
            ':title' => $data['title'],
            ':category' => $data['category'],
            ':description' => $data['description'],
            ':houseNumber' => $data['houseNumber'],
            ':streetName' => $data['streetName'],
            ':county' => $data['county'],
            ':town' => $data['town'],
            ':postcode' => $data['postcode'],
            ':lng' => $data['lng'],
            ':lat' => $data['lat'],
            ':contributor' => $data['contributor'],
            ':status' => $data['status']
        ]);
    }

    /**
     *
     * Edit the status of a facility (used via AJAX for managers).
     */
    public function updateStatus($id, $status): bool {
        $statement = $this->_dbHandle->prepare("UPDATE ecoFacilities SET status = :status WHERE id = :id");
        return $statement->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Delete a facility by its ID (used via AJAX for managers).
     */
    public function deleteFacility($id): bool {
        $statement = $this->_dbHandle->prepare("DELETE FROM ecoFacilities WHERE id = :id");
        return $statement->execute([':id' => $id]);
    }

    /**
     * Get a limited set of facilities for pagination display.
     * Used on the homepage with 20-per-page.
     */
    public function fetchPaginatedFacilities(int $limit, int $offset): array {
        $statement = $this->_dbHandle->prepare("SELECT * FROM ecoFacilities ORDER BY id LIMIT :lim OFFSET :off");
        $statement->bindValue(':lim', $limit, PDO::PARAM_INT);
        $statement->bindValue(':off', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all facility categories (used to populate dropdowns)
     */
    public function fetchCategories(): array {
        $statement = $this->_dbHandle->prepare("SELECT id, name FROM ecoCategories ORDER BY name ASC");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all facility status options (used in the dropdown when adding/editing)
     */
    public function fetchStatuses(): array {
        $statement = $this->_dbHandle->prepare("SELECT id, statusComment FROM ecoFacilityStatus ORDER BY id ASC");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count all facilities — used to calculate total number of pages
     */
    public function countFacilities(): int {
        return (int) $this->_dbHandle->query("SELECT COUNT(*) FROM ecoFacilities")->fetchColumn();
    }
}