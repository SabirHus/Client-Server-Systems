<?php

/*
 * Class Facility Data
 *
 * Represents data for each facility
 */
class FacilityData
{
    protected $_id, $_title,
        $_category, $_description,
        $_houseNumber, $_streetName,
        $_county, $_town, $_postcode,
        $_lng, $_lat, $_contributor, $_status; // class for each facility

    public function __construct($dbRow) { // initialises the variables to the corresponding row in the database
        $this->_id = $dbRow['id'];
        $this->_title = $dbRow['title'];
        $this->_category = $dbRow['category'];
        $this->_description = $dbRow['description'];
        $this->_houseNumber = $dbRow['houseNumber'];
        $this->_streetName = $dbRow['streetName'];
        $this->_county = $dbRow['county'];
        $this->_town = $dbRow['town'];
        $this->_postcode = $dbRow['postcode'];
        $this->_lng = $dbRow['lng'];
        $this->_lat = $dbRow['lat'];
        $this->_contributor = $dbRow['contributor'];
        $this->_status = $dbRow['status'];
    }
    // accessors
    public function getID() { // id accessor
        return $this->_id;
    }

    public function getTitle() { // title accessor
        return $this->_title;
    }

    public function getCategory() { // category accessor
        return $this->_category;
    }

    public function getDescription() { // description accessor
        return $this->_description;
    }
    public function getHouseNumber() { // house number accessor
        return $this->_houseNumber;
    }
    public function getStreetName() { // street name accessor
        return $this->_streetName;
    }
    public function getCounty() { // county accessor
        return $this->_county;
    }

    public function getTown() { // town accessor
        return $this->_town;
    }

    public function getPostCode() { // post code accessor
        return $this->_postcode;
    }
    public function getLng() { // longitude accessor
        return $this->_lng;
    }

    public function getLat() { // latitude accessor
        return $this->_lat;
    }

    public function getContributor() { // contributor accessor
        return $this->_contributor;
    }

    public function getStatus() { // status accessor
        return $this->_status;
    }

}