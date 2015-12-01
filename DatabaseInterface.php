<?php

/**
 * Created by PhpStorm.
 * User: Mio
 * Date: 13.11.2015
 * Time: 18:36
 */
class DatabaseInterface
{
    // protected $conn;

    /** constructs an interface to any connection you choose to. */
    public function __construct($server, $username, $password, $dbname){
        $this->conn = new mysqli($server, $username, $password, $dbname);
        mysqli_set_charset($this->conn, "utf8");
    }

    /** dispatches a database request */
    protected function __dispatch($query, $types, $arguments) {
        print_r($query);
        print_r($types);
        print_r($arguments);
        // @CLEANUP:
        // error_reporting(0);
        $query = $this->conn->prepare($query);
        if(!$query) throw new Exception($this->conn->error);
        //call_user_func(array($query, "bind_param"), array_merge(array($types), $arguments));
        // $query->bind_param(array($types, $arguments));
        $this->__bind($query, $types, $arguments);
        if(!$query) throw new Exception("Query exception: " . $this->conn->error);
        $query->execute();
        return $query->get_result();
    }

    protected function __bind($query, $types, $arguments) {
        if($types !== "") {
            call_user_func_array(array(&$query, 'bind_param'), array_merge(array($types), $arguments));
        }

    }

    protected function map_response($keys, $result_set) {
        if(count($keys) !== count($result_set[0])) throw new Exception("Invalid length");
        $container = [];
        for($i = 0; $i < count($keys); $i++) {
            $container[$i] = [$keys[$i] => $result_set[0][$i]];
        }
        return $container;
    }

    /** get a studio for a long/lat and max distance. page returns the next values after that
      * it serves as key,value example as well
      */
    public function get_studios($page, $distance, $long, $lat){
        $query = $this->GET_STUDIOS_IN_RANGE;
        $types = "";
        $this->__dispatch("SET @lat = ?", "d", array(&$lat));
        $this->__dispatch("SET @lon = ?", "d", array(&$long));
        $this->__dispatch("SET @dist = ?", "d", array(&$distance));
        if ($page !== "") {
            $query .= " OFFSET ?*25";
            $types .= "i";
            return $this->__dispatch($query, $types, array(&$page))->fetch_all();
        }
        $result = $this->__dispatch($query, $types, [])->fetch_all();
        return $this->map_response(["studio_name", "phone", "zip", "location", "street_name",
                                        "street_number", "forename", "name", "studio_description", "distance"], $result);
    }

    /** check if a location and a zip_code are in our database - resolves naming conflicts */
    public function verifyZip($zip_code, $location) {
        if (!isset($zip_code) || !isset($location)) return false;
        $query = $this->VERIFY_ZIP;
        $types = "is";
        $result = $this->__dispatch($query, $types, array(&$zip_code, &$location))->fetch_all();
        return count($result) > 0;
    }

    /** Hint max. 15 locations for a plz */
    public function hintLocations($zip_code) {
        if (!isset($zip_code)) throw new Exception("$zip_code must not be empty.");
        $query = $this->HINT_LOCATIONS;
        $types = "s";
        return $this->__dispatch($query, $types, array(&$zip_code))->fetch_all();
    }

    /** Check now if username is already given */
    public function registerUser($password, $username) {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
        if ($hash == false) throw new Exception("Password hashing failed, please try again");
        $query_exists = $this->SELECT_SINGLE_USER;
        $types = "s";
        $result = $this->__dispatch($query_exists, $types, array(&$username))->fetch_all();
        if(count($result) > 0) throw new Exception("Username exists already.");
        $query = $this->REGISTER_NEW_USER;
        $types = "ss";
        // yields nothing, is insert.
        $this->__dispatch($query, $types, array(&$username, &$hash));
    }

    /** verify a users credentials */
    public function login($username, $password) {
        $query = $this->GET_PASSWORD_HASH_FOR_USER;
        $types = "s";
        $result = $this->__dispatch($query, $types, array(&$username))->fetch_all();
        if(count($result)<1) return false;
        return password_verify($password, $result[0][0]);
    }

    public function insertStudio($studio_name, $studio_type, $street_name, $street_nr,
                                 $geo_long, $geo_lat, $zip, $studio_phone, $creator, $owner = null) {
        $owner_id = null;
        $creator_id = null;
        if($owner) { // the API is responsible for checking completeness of data
            $this->insertPerson($owner["forename"], $owner["name"], $owner["street_name"],
                $owner["street_nr"], $owner["geo_long"], $owner["geo_lat"],
                $owner["zip"], $owner["phone"], "owner");
            $owner_id = $this->conn->insert_id;
        }
        // write a new address into the database
        $this->__dispatch($this->INSERT_ADDRESS, "ssddi", array(&$street_name,
                          &$street_nr, &$geo_long, &$geo_lat, &$zip));
        $address_id = $this->conn->insert_id;

        if($creator) {
            /*
            $this->__dispatch($this->INSERT_STUDIO, "ssddi", array(&$studio_name, &$this->conn->insert_id,
                &$studio_type, &$studio_phone, &$owner_id, &$creator));
            */
        }
        $this->__dispatch($this->INSERT_STUDIO, "sissis",
            array(&$studio_name, &$address_id, &$studio_type,
                  &$studio_phone, &$owner_id, &$creator_id));
        return true;
    }

    protected function insertPerson($forename, $name, $street_name, $street_nr,
                                    $geo_long, $geo_lat, $zip, $phone, $type) {
        $this->__dispatch($this->INSERT_ADDRESS, "ssddi", array(&$street_name,
                          &$street_nr, &$geo_long, &$geo_lat, &$zip));
        $this->__dispatch($this->INSERT_PERSON,  "issis", array(&$type,
                          &$forename, &$name, $this->conn->insert_id, &$phone));
        return true;
    }

    protected $INSERT_STUDIO =
        "INSERT INTO studios(studio_name, address, studio_type, phone, owner, creator, created)
         VALUES     (?,
                     ?,
                    (SELECT id FROM studio_types WHERE type_name = ?),
                     ?,
                    (SELECT id FROM persons WHERE id = ?),
                    (SELECT id FROM users WHERE username = ?),
                     NOW())";

    protected  $INSERT_PERSON =
        "INSERT INTO persons(type, first_name, last_name, address, phone, created)
         VALUES      ((SELECT id FROM person_types WHERE person_name = ?),
                      ?, ?,
                      ?,
                      ?, NOW())";



    protected $INSERT_ADDRESS =
        "INSERT INTO addresses(street_name, stree_nr, geo_long, geo_lat, location)
         VALUES (?, ?, ?, ?, (SELECT id FROM locations WHERE zip_code = ?))";

    protected $GET_STUDIOS_IN_RANGE =
        "SELECT studios.studio_name,
                studios.phone,
                locations.zip_code,
                locations.location_name,
                addresses.street_name,
                addresses.stree_nr,
                persons.first_name,
                persons.last_name,
                studio_types.description,
                p.distance_unit
                    * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                    * COS(RADIANS(addresses.geo_lat))
                    * COS(RADIANS(p.longpoint) - RADIANS(addresses.geo_long ))
                    + SIN(RADIANS(p.latpoint))
                    * SIN(RADIANS(addresses.geo_lat)))) AS distance
        FROM 	studios
        JOIN	(   /* these are the query parameters */
                  SELECT  @lat AS latpoint, @lon AS longpoint,
                          @dist AS radius,   111.045 AS distance_unit
                ) AS p ON 1=1
        CROSS JOIN 	addresses
        ON 			studios.address = addresses.id
        CROSS JOIN 	locations
        ON 			addresses.location = locations.id
        CROSS JOIN 	persons
        ON 			studios.owner = persons.id
        CROSS JOIN 	studio_types
        ON 			studios.studio_type = studio_types.id
        WHERE 		addresses.geo_lat
            BETWEEN p.latpoint  - (p.radius / p.distance_unit)
                AND p.latpoint  + (p.radius / p.distance_unit)
                AND addresses.geo_long
            BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
                AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
        ORDER BY 	distance
        LIMIT 25";

    protected $VERIFY_ZIP =
        "SELECT zip_code, location_name
         FROM   locations WHERE zip_code = ? AND location_name = ? LIMIT 1";

    protected $HINT_LOCATIONS =
        "SELECT location_name, zip_code FROM locations
         WHERE CONVERT(zip_code, CHAR(5)) LIKE CONCAT('%', ?, '%') LIMIT 15";

    protected $SELECT_SINGLE_USER =
        "SELECT * FROM users WHERE username = lower(?) LIMIT 1";

    protected $REGISTER_NEW_USER =
        "INSERT INTO users(username, password_hash, user_role, created) VALUES (LOWER(?), ?, 1, NOW())";

    protected $GET_PASSWORD_HASH_FOR_USER =
        "SELECT password_hash FROM users WHERE username = LOWER(?) AND verified > 0 LIMIT 1";
}