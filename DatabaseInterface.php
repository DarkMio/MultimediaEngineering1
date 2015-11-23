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
        mysqli_set_charset($this->conn, "utf8");
    }

    protected function __dispatch($query, $types, $arguments) {
        error_reporting(0);
        $query = $this->conn->prepare($query);
        //call_user_func(array($query, "bind_param"), array_merge(array($types), $arguments));
        // $query->bind_param(array($types, $arguments));
        call_user_func_array(array(&$query, 'bind_param'), array_merge(array($types), $arguments));
        if(!$query) throw new Exception("Query exception");
        $query->execute();
        return $query->get_result();
    }

    public function get_studios($offset, $long, $lat){
        if (!isset($long) && !isset($lat)) throw new Exception("no long / lat parameter set");
        $query = "SELECT studios.name, studios.phone, locations.zip_code, locations.location_name, addresses.street_name, addresses.stree_nr, persons.first_name, persons.last_name
                  FROM studios
                  CROSS JOIN addresses
                  ON studios.address = addresses.id
                  CROSS JOIN locations
                  ON addresses.location = locations.id
                  CROSS JOIN persons
                  ON studios.owner = persons.id
                  CROSS JOIN studio_types
                  ON studios.studio_type = studio_types.id
                  WHERE abs(addresses.geo_long - ?) < 0.5
				  AND abs(addresses.geo_lat - ?) < 0.5";
        $types = "dd";
        if (isset($offset)) {
            $query .= " LIMIT 25 OFFSET ?";
            $types .= "i";
            return $this->__dispatch($query, $types, array(&$long, &$lat, &$offset))->fetch_all();
        }
        return $this->__dispatch($query, $types, array(&$long, &$lat))->fetch_all();
    }

    public function verifyZip($zip_code, $location) {
        if (!isset($zip_code) || !isset($location)) return false;
        $query = "SELECT zip_code, location_name FROM locations WHERE zip_code = ? AND location_name = ? LIMIT 1";
        $types = "is";
        $result = $this->__dispatch($query, $types, array(&$zip_code, &$location))->fetch_all();
        return count($result) > 0;
    }

    public function hintLocations($zip_code) {
        if (!isset($zip_code)) throw new Exception("$zip_code must not be empty.");
        $query = "SELECT location_name, zip_code FROM locations
                  WHERE CONVERT(zip_code, CHAR(5)) LIKE CONCAT('%', ?, '%') LIMIT 15";
        $types = "s";
        $result = $this->__dispatch($query, $types, array(&$zip_code))->fetch_all();
        // var_dump(array($result));
        return $result;
    }

    public function registerUser($password, $username) {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
        if ($hash == false) throw new Exception("Password hashing failed, please try again");
        $query = "INSERT INTO users(username, password_hash, user_role) VALUES (LOWER(?), ?, 1)";
        $types = "ss";
        // yields nothing, is insert.
        $this->__dispatch($query, $types, array(&$username, &$hash));
    }

    public function login($username, $password) {
        // @TODO: Doesn't check is user is activated
        $query = "SELECT password_hash FROM users WHERE username = LOWER(?) LIMIT 1";
        $types = "s";
        $result = $this->__dispatch($query, $types, array(&$username))->fetch_all();
        if(count($result)<1) return false;
        return password_verify($password, $result[0][0]);
    }
}