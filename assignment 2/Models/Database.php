<?php
/*
 * Class Database
 *
 * This class creates the connection between the website and the database
 */
class Database {
    /**
     * @var Database
     */
    protected static $_dbInstance = null;

    /**
     * @var PDO
     */
    protected $_dbHandle; // create the variable dbHandle

    /**
     * @return Database
     */
    public static function getInstance() {

       if(self::$_dbInstance === null) { // checks if the PDO exists
            // creates new instance if not, sending in connection info
            self::$_dbInstance = new self();
        }
        return self::$_dbInstance; // return instance
    }

    private function __construct() {
        try {
             $this->_dbHandle = new PDO("sqlite:ecobuddy.sqlite"); // connects to the database at this location
        }
        catch (PDOException $e) { // catch any failure to connect to the database
	    echo $e->getMessage();
	}
    }

    /**
     * @return PDO
     */
    public function getDbConnection() {
        return $this->_dbHandle; // returns the PDO handle to be used elsewhere
    }

    public function __destruct() {
        $this->_dbHandle = null; // destroys the PDO handle when no longer needed
    }
}
