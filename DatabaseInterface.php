<?php

/**
 * Created by PhpStorm.
 * User: Mio
 * Date: 13.11.2015
 * Time: 18:36
 */
class DatabaseInterface
{
    protected $server = '';
    protected $username = '';
    protected $password = '';
    protected $dbname = '';
    protected $conn = '';
    public function __construct($server, $username, $password, $dbname){
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->conn = new mysqli($server, $username, $password, $dbname);
    }

    protected function __dispatch($query, $types, $arguments) {
        $query = $this->conn->prepare($query);
        call_user_func(array($query, "bind_param"), array_merge(array($types), $arguments));
        $query->bind_param($types, $arguments);
        $query->execute();
        return $query->get_result()->fetch_all();
    }

    public function get_studios($offset, $long, $lat){
        if (!isset($long) && !isset($lat)) throw new Exception("no long / lat parameter set");
        $query = "SELECT * FROM studios
                  CROSS JOIN ON adresses
                  WHERE studios.address == adresses.id
                  AND abs(adresses.geo_long - ?) < 0.5
                  AND abs(adresses.geo_lat - ?) < 0.5
                  CROSS JOIN ON persons
                  WHERE studios.owner == persons.id
                  CROSS JOIN ON studio_types
                  WHERE studios.studio_type == studio_types.id";
        $types = "ii";
        if (isset($offset)) {$query .= " OFFSET ?"; $types .= "i";}
        return $this->__dispatch($query, $types, array(&$long, &$lat, &$offset));
    }

    public function verifyZip($zip_code, $location) {
        if (!isset($zip_code) || !isset($location)) return false;
        $query = "SELECT zip_code FROM locations WHERE zip_code = ? AND location_name = ? LIMIT 1";
        $types = "is";
        $result = $this->__dispatch($query, $types, array($zip_code, $location));
        return isset($result);
    }

    public function hintLocations($zip_code) {
        if (!isset($zip_code)) throw new Exception("$zip_code must not be empty.");
        $query = "SELECT location_name FROM locations WHERE zip_code = ?";
        $types = "i";
        return $this->__dispatch($query, $types, array($zip_code));
    }


}