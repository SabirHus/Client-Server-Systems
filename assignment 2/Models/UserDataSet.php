<?php
require_once ('Database.php');   // Connects to the database class
require_once ('UserData.php');   // Loads the user model class

class UserDataSet {
    protected $_dbHandle, $_dbInstance;

    // Constructor: Sets up the database connection using Singleton pattern
    public function __construct() {
        $this->_dbInstance = Database::getInstance();               // Reuse the single DB connection
        $this->_dbHandle = $this->_dbInstance->getDbConnection();   // Actual PDO connection handle
    }

    /**
     * Validates login credentials
     * If valid, saves user info to the session
     * Uses JOIN to fetch readable user type (Manager/User)
     */
    public function checkUserCredentials($username, $password) {
        // Query selects user and joins their type from ecoUsertypes
        $sql = "
        SELECT u.id, u.username, u.password, t.name AS userType
        FROM ecoUser u
        JOIN ecoUsertypes t ON u.userType = t.id
        WHERE u.username = :username
        ";

        // Prepare and bind the username securely
        $statement = $this->_dbHandle->prepare($sql);
        $statement->bindParam(':username', $username);
        $statement->execute();


        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Compare the submitted password with stored password
        if ($user && $password === $user['password']) {
            // Save important session info for use across the app
            $_SESSION['userID'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['userType'] = $user['userType'];
            return true;
        }

        // If login fails, return false
        return false;
    }
}