<?php

include "APIExceptions.php";

class DatabaseInterface
{
    /**
     * constructs an interface to any connection you choose to.
     * @param $server
     * @param $username
     * @param $password
     * @param $dbname
     */
    public function __construct($server, $username, $password, $dbname){
        $this->conn = new mysqli($server, $username, $password, $dbname);
        mysqli_set_charset($this->conn, "utf8");
    }

    /**
     * dispatches a database request
     * @param $query
     * @param $types
     * @param $arguments
     * @return bool|mysqli_result
     * @throws Exception
     */
    protected function __dispatch($query, $types, $arguments) {
        // @CLEANUP:
        // error_reporting(0);
        $query = $this->conn->prepare($query);
        // full verbosity about database errors - helps debugging.
        if(!$query) throw new Exception("Preparing exception: " . $this->conn->error);
        if($types !== "") call_user_func_array(array(&$query, 'bind_param'), array_merge(array($types), $arguments));
        if(!$query) throw new Exception("Query exception: " . $this->conn->error);
        $query->execute();
        if(!$query) throw new Exception("Execution exception: " . $this->conn->error);
        return $query->get_result();
    }

    /**
     * Makes a sick dictionary out of keys / values
     * @param $keys String array
     * @param $result_set array of json-able objects
     * @return array of associated array
     * @throws Exception Debug exception - if your result set length is not equal of key array length
     */
    protected function map_response_single($keys, $result_set) {
        if(count($keys) !== count($result_set))
            throw new Exception("Invalid length");
        $container = [];
        for($i = 0; $i < count($keys); $i++)
            $container += [$keys[$i] => $result_set[$i]];
        return $container;
    }

    /**
     * Maps a collection with given keys each.
     * @param $keys
     * @param array $results
     * @return array
     * @throws Exception
     */
    protected function map_response_multiple($keys, array $results) {
        for($i = 0; $i < count($results); $i ++)
            $results[$i] = $this->map_response_single($keys, $results[$i]);
        return $results;
    }

    /**
     * Searches the user_roles table if a certain user right is higher than what you're trying to do
     * @param $targetRight
     * @param $userRight
     * @return bool
     * @throws Exception
     */
    protected function checkUserRights($targetRight, $userRight) {
        while ($userRight) {
            $userRight = $this->__dispatch($this->GET_PARENT_USER_ROLE, "d", array(&$userRight))->fetch_row()[0];
            if ($userRight == $targetRight) return true;
        }
        throw new Exception("Not enough rights to execute this API endnode.");
    }

    /**
     * get a studio for a long/lat and max distance. page returns the next values after that
     * it serves as key,value example as well
     * @param $page
     * @param $distance
     * @param $long
     * @param $lat
     * @return array|mixed
     * @throws Exception
     */
    public function get_studios($page, $distance, $long, $lat){
        $this->__dispatch("SET @lat = ?", "d", array(&$lat));
        $this->__dispatch("SET @lon = ?", "d", array(&$long));
        $this->__dispatch("SET @dist = ?", "d", array(&$distance));
        if ($page != "") {
            // expecting page = 1 yields in page = 0
            // and then we can start pagination - with empty results once we went through all data.
            $page = max($page-1, 0) * 25;
            $result = $this->__dispatch($this->GET_STUDIOS_IN_RANGE_WITH_OFFSET, "d", array(&$page))->fetch_all();
        } else {
            $result = $this->__dispatch($this->GET_STUDIOS_IN_RANGE_WITHOUT_OFFSET, "", array())->fetch_all();
        }
        if($result) {
            return $this->map_response_multiple(["id", "studio_name", "phone", "zip", "location", "street_name",
               "street_number", "forename", "name", "studio_description", "distance"], $result);
        }
        return ($result);
    }

    /**
     * Debug Function - just to get all studios via a request
     * @return mixed
     * @throws Exception
     */
    public function get_all_studios() {
        return $this->__dispatch($this->GET_ALL_STUDIOS, "", array())->fetch_all();
    }

    /**
     * Verifies a user key for the api.
     * @param $key
     * @param $username
     * @return bool
     * @throws Exception
     */
    public function verifyKey($key, $username, $minRole = null) {
        $result = $this->__dispatch($this->VERIFY_KEY_AND_GET_ROLE, "ss", array(&$key, &$username))->fetch_row();
        if(count($result) == 0)
            throw new Exception("Invalid user credentials");
        if($minRole)
            return $this->checkUserRights($minRole, $result[0]);
        return count($result) > 0;
    }

    /**
     * check if a location and a zip_code are in our database - resolves naming conflicts
     * @param $zip_code
     * @param $location
     * @return bool
     * @throws Exception
     */
    public function verifyZip($zip_code, $location) {
        $result = $this->__dispatch($this->VERIFY_ZIP, "is", array(&$zip_code, &$location))->fetch_all();
        return count($result) > 0;
    }


    /**
     * hint max. 15 locations for a plz
     * @param $zip_code
     * @return mixed
     * @throws Exception
     */
    public function hintLocations($zip_code) {
        if (!isset($zip_code))
            throw new Exception("$zip_code must not be empty.");
        return $this->__dispatch($this->HINT_LOCATIONS, "s", array(&$zip_code))->fetch_all();
    }

    /**
     * Check now if username is already given
     * @param $password
     * @param $username
     * @throws Exception
     */
    public function registerUser($password, $username) {
        // check if a username is already given...
        $result = $this->__dispatch($this->SELECT_SINGLE_USER, "s", array(&$username))->fetch_all();
        if(count($result) > 0)
            throw new FailStateException(["Exception" => "Username exists already."]);
        // encrypt his password
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
        if ($hash == false)
            throw new Exception("Registration failed, please try again");
        // yields nothing, is insert.
        $this->__dispatch($this->REGISTER_NEW_USER, "ss", array(&$username, &$hash));
    }

    /**
     * User logs in with this function - gets a user token that is valid for 24h
     * @param $username
     * @param $password
     * @return array
     * @throws Exception
     */
    public function login($username, $password) {
        $fail = ["success" => false]; // shortcut
        $result = $this->__dispatch($this->GET_PASSWORD_HASH_FOR_USER, "s", array(&$username))->fetch_all();
        if(count($result)<1) return $fail;
        if(password_verify($password, $result[0][0])) {
            return $this->generateUserKeys($username);
        } else {
            return $fail;
        }
    }

    /**
     * Generates new user keys and returns them with a properly formatted api
     * @param $username
     * @return array
     * @throws Exception
     */
    public function generateUserKeys($username) {
        // check if there is a key
        $result = $this->__dispatch($this->SELECT_USER_KEY, "s", array(&$username))->fetch_all();

        if(count($result) > 0) { // if so, update with new keys (no concurrent users are possible)
            $this->__dispatch($this->REGENERATE_USER_KEY, "s", array(&$username));
        } else { // if not, create a completely new key
            $this->__dispatch($this->GENERATE_USER_KEY, "s", array(&$username));
        } // and then return the key
        return $this->map_response_single(["success", "token", "valid_until"], array_merge([true], $this->__dispatch($this->SELECT_USER_KEY, "s", array(&$username))->fetch_all()[0]));
    }

    /**
     * Inserts a studio into the database
     * @TODO: currently only inserts into the live db.
     * @param $studio_name
     * @param $studio_type
     * @param $street_name
     * @param $street_nr
     * @param $geo_long
     * @param $geo_lat
     * @param $zip
     * @param $studio_phone
     * @param $creator
     * @param $location
     * @param null $owner
     * @return array
     * @throws Exception
     */
    public function insertStudio($studio_name, $studio_type, $street_name, $street_nr,
                                 $geo_long, $geo_lat, $zip, $location, $studio_phone, $creator,
                                 $owner = null, $into_live=false) {
        $owner_id = null;
        $creator_id = null;
        if($owner) { // the API is responsible for checking completeness of data
            $this->insertPerson($owner["forename"], $owner["name"], $owner["street_name"],
                $owner["street_nr"], $owner["geo_long"], $owner["geo_lat"],
                $owner["zip"], $owner["location"], $owner["phone"], $owner["type"]);
            $owner_id = $this->conn->insert_id;
        }

        if(!$this->verifyZip($zip, $location)) throw new Exception("Address not in register");
        // write a new address into the databaseperson_types
        $this->__dispatch($this->INSERT_ADDRESS, "ssddis", array(&$street_name,
                          &$street_nr, &$geo_long, &$geo_lat, &$zip, &$location));
        $address_id = $this->conn->insert_id;

        if(isset($creator)) {
            $creator_id = $this->__dispatch($this->SELECT_CREATOR_ID, "s", array(&$creator))->fetch_row();
        }

        // Moderators can insert instantly into live
        if($into_live) {
            $this->__dispatch($this->INSERT_STUDIO_IN_LIVE, "sissis",
                array(&$studio_name, &$address_id, &$studio_type,
                    &$studio_phone, &$owner_id, &$creator_id));
        } else {
            $this->__dispatch($this->INSERT_STUDIO_IN_STAGING, "sissis",
                array(&$studio_name, &$address_id, &$studio_type,
                    &$studio_phone, &$owner_id, &$creator_id));
        }
        return ["success" => true];
    }

    /**
     * Inserts a complete dataset for a person.
     * @param $forename
     * @param $name
     * @param $street_name
     * @param $street_nr
     * @param $geo_long
     * @param $geo_lat
     * @param $zip
     * @param $location
     * @param $phone
     * @param $type
     * @return array
     * @throws Exception
     */
    protected function insertPerson($forename, $name, $street_name, $street_nr,
                                    $geo_long, $geo_lat, $zip, $location, $phone, $type) {
        $this->__dispatch($this->INSERT_ADDRESS, "ssddis", array(&$street_name,
                          &$street_nr, &$geo_long, &$geo_lat, &$zip, &$location));
        $address_id = $this->conn->insert_id;
        if(!$address_id) throw new Exception("API Request failed - address not written");
        $this->__dispatch($this->INSERT_PERSON,  "issis", array(&$type,
                          &$forename, &$name, &$address_id, &$phone));
        if(!$this->conn->insert_id) throw new Exception("API Request failed - person not written.");
        return ["success" => true];
    }

    public function showStaged($amount=50, $page=0) {
        $result = $this->__dispatch($this->GET_ALL_STAGED, "ii", array(&$amount, &$page))->fetch_all();
        $response = $this->map_response_multiple(["id", "name", "street", "street_nr", "zip",
            "location", "lat", "long", "phone", "type", "description", "owner"], $result);
        $return_val = [];
        foreach($response as $single) {
            if($single["owner"]) {
                $res = $this->__dispatch($this->GET_OWNER, "i", array(&$single["owner"]))->fetch_row();
                /*p.id, p.first_name, p.last_name, ad.street_name, ad.stree_nr,
                    loc.zip_code, loc.location_name, ad.geo_long, ad.geo_lat,
                    pt.person_name, pt.description*/
                $single["owner"] = $this->map_response_single(["id", "first_name", "last_name",
                    "street_name", "street_nr", "zip", "location", "lat", "long", "type", "description"], $res);
            }
            $return_val[] = $single;
        }
        return $return_val;
    }

    public function rate($score, $studio, $username, $ip) {
        $has_entry = $this->__dispatch($this->SELECT_STUDIO_RATING, "i", array(&$studio))->fetch_row();
        if(count($has_entry) == 0) {
            $this->__dispatch($this->INSERT_FIRST_RATING, "i", array(&$studio));
        }
        // needs ip processor
        $this->__dispatch($this->SINGLE_RATING, "iiss", array(&$score, &$studio, &$username, &$ip));
        $this->__dispatch($this->UPDATE_RATING, "i", array(&$studio));
    }

    public function accept_staged($id) {
        $this->__dispatch($this->MOVE_TO_STUDIOS, "i", array(&$id));
        $this->delete_staged($id);
    }

    public function delete_staged($id) {
        $this->__dispatch($this->DELETE_STAGED, "i", array(&$id));
    }

    /*****************************************************
     * Following are constants containing DB statements. *
     * ------------------------------------------------- *
     * There is no indication of types and security.     *
     *****************************************************/
    protected $MOVE_TO_STUDIOS = "
    INSERT INTO studios (id, studio_name, address, studio_type, owner, creator, phone, created)
    SELECT id, studio_name, address, studio_type, owner, creator, phone, created FROM studios_staging WHERE id = ?
    ";

    protected $DELETE_STAGED = "DELETE FROM studios_Staging WHERE id = ?";

    protected $INSERT_FIRST_RATING = "
        INSERT INTO studio_ratings(studio, count, avg)
        VALUES      (?, 0, 0)
        ";

    protected $SELECT_STUDIO_RATING = "
        SELECT id
        FROM studio_ratings
        WHERE studio = ?
        LIMIT 1
        ";

    protected $UPDATE_RATING = "
        UPDATE      studio_ratings
        INNER JOIN  (
            SELECT  studio,
                    count(score) AS c,
                    avg(score) AS a
            FROM    single_rating
            WHERE   studio = ?
          ) t1
        ON          studio_ratings.studio = t1.studio
        SET         count = c,
                    avg = a";

    protected $SINGLE_RATING = "
        INSERT INTO single_rating(score, studio, user_ref, created, ip)
        VALUES      (?,
                     ?,
                     (SELECT id FROM users WHERE lower(username) = LOWER(?)),
                     NOW(),
                     ?)
    ";

    protected $GET_OWNER =
        "SELECT     p.id, p.first_name, p.last_name, ad.street_name, ad.stree_nr,
                    loc.zip_code, loc.location_name, ad.geo_lat, ad.geo_long,
                    pt.person_name, pt.description
         FROM       persons as p
         CROSS JOIN person_types as pt
         ON         p.type = pt.id
         CROSS JOIN addresses as ad
         ON         p.address = ad.id
         CROSS JOIN locations as loc
         ON         ad.location = loc.id
         WHERE      p.id = ?
         AND        LOWER(pt.person_name) = 'studio owner'
         LIMIT      1";

    protected $GET_ALL_STAGED =
        "SELECT         ss.id, ss.studio_name, ad.street_name, ad.stree_nr,
                        loc.zip_code, loc.location_name, ad.geo_lat, ad.geo_long,
                        ss.phone, st.type_name, st.description, ss.owner
         FROM           studios_staging as ss
         CROSS JOIN 	addresses as ad
         ON 			ss.address = ad.id
         CROSS JOIN 	studio_types as st
         ON 			ss.studio_type = st.id
         CROSS JOIN 	locations as loc
         ON 			ad.location = loc.id
         ORDER BY       ss.id
         LIMIT          ?
         OFFSET         ?";

    protected $GET_PARENT_USER_ROLE =
        "SELECT parent FROM user_roles WHERE id = ? LIMIT 1";

    protected $GET_ALL_STUDIOS =
        "SELECT studios.studio_name,
                studios.phone,
                locations.zip_code,
                locations.location_name,
                addresses.street_name,
                addresses.stree_nr,
                persons.first_name,
                persons.last_name,
                studio_types.description
        FROM 	studios
        CROSS JOIN 	addresses
        ON 			studios.address = addresses.id
        CROSS JOIN 	locations
        ON 			addresses.location = locations.id
        LEFT OUTER JOIN 	persons
        ON 			studios.owner = persons.id
        CROSS JOIN 	studio_types
        ON 			studios.studio_type = studio_types.id
        ORDER BY 	studios.id
        LIMIT 0,25";

    protected $GET_ROW_NUMBER_OF_STUDIOS =
        "SELECT COUNT(*) FROM studios";

    protected $SELECT_USER_KEY =
        "SELECT user_token, valid_until
         FROM   user_login_token
         WHERE  user_id = (SELECT id FROM users WHERE LOWER(username) = LOWER(?))
         LIMIT  1";

    protected $REGENERATE_USER_KEY =
        "UPDATE user_login_token
         SET    user_token = SUBSTRING(MD5((UNIX_TIMESTAMP(NOW()) * 1000000 + MICROSECOND(NOW(6)))),1,32),
                valid_until = NOW() + INTERVAL 1 DAY
         WHERE  user_id = (SELECT id FROM users WHERE LOWER(username) = LOWER(?))
         LIMIT  1";

    protected $GENERATE_USER_KEY =
        "INSERT INTO user_login_token(user_token, user_id, valid_until)
         VALUES (SUBSTRING(MD5((UNIX_TIMESTAMP(NOW()) * 1000000 + MICROSECOND(NOW(6)))),1,32),
                 (SELECT id FROM users WHERE LOWER(username) = LOWER(?)),
                 NOW() + INTERVAL 1 DAY)";

    protected $SELECT_CREATOR_ID =
        "SELECT id FROM users WHERE LOWER(username) = LOWER(?) LIMIT 1";

    protected $INSERT_STUDIO_IN_LIVE =
        "INSERT INTO studios(studio_name, address, studio_type, phone, owner, creator, created)
         VALUES     (?,
                     ?,
                    (SELECT id FROM studio_types WHERE LOWER(type_name) = LOWER(?)),
                     ?,
                    (SELECT id FROM persons WHERE id = ?),
                    (SELECT id FROM users WHERE LOWER(username) = LOWER(?)),
                     NOW())";

    protected $INSERT_STUDIO_IN_STAGING =
        "INSERT INTO studios_staging(studio_name, address, studio_type, phone, owner, creator, created)
         VALUES     (?,
                     ?,
                    (SELECT id FROM studio_types WHERE LOWER(type_name) = LOWER(?)),
                     ?,
                    (SELECT id FROM persons WHERE id = ?),
                    (SELECT id FROM users WHERE LOWER(username) = LOWER(?)),
                     NOW())";

    protected  $INSERT_PERSON =
        "INSERT INTO persons(type, first_name, last_name, address, phone, created)
         VALUES      ((SELECT id FROM person_types WHERE LOWER(person_name) = LOWER(?)),
                      ?,
                      ?,
                      ?,
                      ?,
                      NOW())";

    protected $INSERT_ADDRESS =
        "INSERT INTO addresses(street_name, stree_nr, geo_long, geo_lat, location)
         VALUES (?, ?, ?, ?, (SELECT id FROM locations WHERE zip_code = ? AND LOWER(location_name) = LOWER(?)))";

    protected $GET_STUDIOS_IN_RANGE_WITHOUT_OFFSET =
        "SELECT studios.id,
                studios.studio_name,
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
        LEFT OUTER JOIN	persons
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
        LIMIT 0,25";

    protected $GET_STUDIOS_IN_RANGE_WITH_OFFSET =
        "SELECT studios.id,
                studios.studio_name,
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
        LEFT OUTER JOIN persons
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
        LIMIT 25
        OFFSET ?";

    protected $VERIFY_KEY_AND_GET_ROLE =
        "SELECT users.user_role
         FROM   user_login_token
         CROSS JOIN users
         ON     user_id = users.id
         CROSS JOIN user_roles
         ON     users.user_role = user_roles.id
         WHERE  valid_until > NOW()
         AND    LOWER(user_token) = LOWER(?) /* the user key is always lower*/
         AND    LOWER(users.username) = LOWER(?)";

    protected $VERIFY_KEY =
        "SELECT * FROM user_login_token
         WHERE  valid_until > NOW()
         AND    LOWER(user_token) = LOWER(?) /* the user key is always lower*/
         AND    user_id = (SELECT id FROM users WHERE LOWER(username) = LOWER(?))
         LIMIT 1";

    protected $VERIFY_ZIP =
        "SELECT zip_code, location_name
         FROM   locations WHERE zip_code = ?
         AND    LOWER(location_name) = LOWER(?)
         LIMIT  1";

    protected $HINT_LOCATIONS =
        "SELECT location_name, zip_code FROM locations
         WHERE CONVERT(zip_code, CHAR(5)) LIKE CONCAT('%', ?, '%')
         LIMIT  15";

    protected $SELECT_SINGLE_USER =
        "SELECT * FROM users WHERE LOWER(username) = LOWER(?) LIMIT 1";

    protected $REGISTER_NEW_USER =
        "INSERT INTO users(username, password_hash, user_role, created) VALUES (LOWER(?), ?, 1, NOW())";

    protected $GET_PASSWORD_HASH_FOR_USER =
        "SELECT password_hash FROM users WHERE username = LOWER(?) AND verified > 0 LIMIT 1";
}