<?php

require_once '../../DatabaseInterface.php';
require_once 'API.class.php';

class MyAPI extends API
{
    protected $User;
    protected $db;
    public function __construct($request, $origin) {
        parent::__construct($request);
        $this->db = new DatabaseInterface("localhost", "root", "1337s1mpl3x", "tattooliste");
        // Abstracted out for example
        $APIKey = "key";//new Models\APIKey();
        $User =  "mio";// new Models\User();

        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if ($APIKey !== $this->request['apiKey']) {// !$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        } else if (array_key_exists('token', $this->request) &&
            !$User->get('token', $this->request['token'])) {

            throw new Exception('Invalid User Token');
        }

        $this->User = $User;
    }

    /**
     * Example of an Endpoint

    protected function locations() {
        if ($this->method == 'GET') {
            if(!isset($this->args["offset"])) $this->args["offset"] = "";
            if(!isset($this->args["zip_code"])) $this->args["zip_code"] = "";
            return $this->pull_locations($this->args["offset"], $this->args["zip_code"]); //$this->User->name;
        } else {
            return "Only accepts GET requests";
        }
    }
     */

    protected function findStudios() {
        parent::checkRequest(["long", "lat", "distance"]);
        $offset = "";
        if(isset($this->request["offset"])) $offset = $this->request["offset"];
        return $this->db->get_studios($offset, $this->request["distance"], $this->request["long"], $this->request["lat"]);
    }

    protected function registerStudio() {
        /** Check location */
        parent::checkRequest(["zip", "long", "lat", "street_name". "street_number", "type", ]);

        /** Fill up possible null values */
    }

    protected function verifyLocation() {
        parent::checkRequest(["zip", "location"]);
        return $this->db->verifyZip($this->request["zip"], $this->request["location"]);
    }

    protected function register() {
        parent::checkRequest(["password", "username"]);
        $this->db->registerUser($this->request["password"], $this->request["username"]);
        return ["register" => "success"];
    }

    protected function login() {
        // if (!isset($this->request["username"])) $this->request["username"] = $this->request["email"]; // override, check again
        parent::checkRequest(["username", "password"]);;
        if ($this->db->login($this->request["username"], $this->request["password"])) return ["login" => "success"];
        throw new Exception("failure - maybe not verified?");
    }

    protected function hintLocations() {
        parent::checkRequest(["zip"]);
        return $this->db->hintLocations($this->request["zip"]);
    }

    protected function addStudio() {
        parent::checkRequest(["studio_street_nr", "studio_name", "studio_type",
            "studio_street_name", "studio_long", "studio_lat", "studio_zip"]);
        $owner = null;
        $r = $this->request;
        if(isset($this->request["owner_name"])) {
            try {
                parent::checkRequest(["owner_street_nr", "owner_name", "owner_type",
                    "owner_street_name", "owner_long", "owner_lat", "owner_zip",
                    "creator"]);
                // make an associated array with owner values - may not need owner_ preface
                // because it generates spaghetti / specialized code-segments.
                $owner = ["owner_name" => $r["owner_name"],
                    "owner_forename" => $r["owner_forename"],
                    "owner_type" => "owner",
                    "owner_street_name" => $r["owner_street_name"],
                    "owner_street_nr" => $r["owner_street_nr"],
                    "owner_long" => $r["owner_long"],
                    "owner_lat" => $r["owner_lat"],
                    "owner_zip" => $r["owner_zip"],
                    "owner_phone" => isset($r["owner_phone"]) ? $r["owner_phone"] : null,
                    "creator" => isset($r["creator"]) ? $r["creator"] : null,
                ];
            } catch (APIException $e) {
                // nothing - maybe return exception info on missing creator params?
            }
        }
        $creator = isset($r["creator"]) ? $r["creator"] : null; // pls don't hurt me.
        $studio_phone = isset($r["studio_phone"]) ? $r["studio_phone"] : null;
        $this->db->insertStudio($r["studio_name"], $r["studio_type"], $r["studio_street_name"],
            $r["studio_street_nr"], $r["studio_long"], $r["studio_lat"], $r["studio_zip"],
            $studio_phone, $creator, $owner);
    }
/*
    protected function pull_locations($offset, $zip_code) {
        $servername = "localhost";
        $username = "root";
        $password = "1337s1mpl3x";
        $dbname = "tattooliste";
        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->set_charset("utf8");

        if (!isset($offset)) {
            if (!isset($zip_code)) {
                $sql_query = "SELECT * FROM locations LIMIT 100 OFFSET ?";
                $sql = $conn->prepare($sql_query);
                $sql->bind_param('i', $offset);
            } else {
                $sql_query = "SELECT * FROM locations WHERE zip_code HAVING ? LIMIT 100 OFFSET ?";
                $sql = $conn-> prepare($sql_query);
                $sql->bind_param('ii', $zip_code, $offset);
            }
        } else {
            $sql_query = "SELECT * FROM locations LIMIT 100";
            $sql = $conn->prepare($sql_query);
        }

        $sql->execute();
        $result = $sql->get_result();
        mysqli_close($conn);
        return $result->fetch_all();
    }
*/

}