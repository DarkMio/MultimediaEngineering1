<?php

require_once 'DatabaseInterface.php';
require_once 'API.class.php';

class MyAPI extends API
{
    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);
        // Disables all other request methods.
        if ($this->method != 'GET') throw new Exception("This API only accepts GET requests");
    }

    /**
     * Debug function - should NEVER be on the production system.
     * @TODO: Cleanup pls - checks now if someone is a moderator, so it's more "secure"
     */
    protected function getAllStudios() {
        parent::verifyKey(2);
        return $this->db->get_all_studios();
    }

    protected function amIMod() {
        return parent::verifyKey(2);
    }

    protected function findStudios() {
        parent::checkRequest(["long", "lat", "distance"]);
        $offset = "";
        if(isset($this->request["page"]))
            $offset = $this->request["page"];
        return $this->db->get_studios($offset, $this->request["distance"], $this->request["long"], $this->request["lat"]);
    }

    protected function verifyLocation() {
        parent::checkRequest(["zip", "location"]);
        return $this->db->verifyZip($this->request["zip"], $this->request["location"]);
    }

    protected function register() {
        return ["info" => "Currently not supported and is disabled."];
/*
        parent::checkRequest(["password", "username"]);
        $this->db->registerUser($this->request["password"], $this->request["username"]);
        // if it didn't throw an error - then it worked.
        return ["register" => "success"];
*/
    }

    protected function login() {
        parent::checkRequest(["username", "password"]);;
        $result = $this->db->login($this->request["username"], $this->request["password"]);
        // overwrite password in request - for security reasons
        $this->request["password"] = "hidden";
        return $result;
    }

    protected function hintLocations() {
        parent::checkRequest(["zip"]);
        return $this->db->hintLocations($this->request["zip"]);
    }

    protected function addStudio() {
        $r = $this->request;
        $owner = null;

        try {
            parent::verifyKey(2); // moderator and above
            $into_live = isset($r["force"]);
        } catch (Exception $e) {
            $into_live = false;
        }

        parent::checkRequest(["studio_street_nr", "studio_name", "studio_type",
            "studio_street_name", "studio_long", "studio_lat", "studio_zip", "studio_location"]);
        if(isset($r["owner_name"])) {
            try {
                parent::checkRequest(["owner_street_nr", "owner_name",
                    "owner_street_name", "owner_long", "owner_lat", "owner_zip", "owner_location"]);
                // make an associated array with owner values - may not need owner_ preface
                // because it generates spaghetti / specialized code-segments.
                $owner = ["name" => $r["owner_name"],
                    "forename" => $r["owner_forename"],
                    "type" => "Studio Owner",
                    "street_name" => $r["owner_street_name"],
                    "street_nr" => $r["owner_street_nr"],
                    "geo_long" => $r["owner_long"],
                    "geo_lat" => $r["owner_lat"],
                    "zip" => $r["owner_zip"],
                    "location" => $r["owner_location"],
                    "phone" => isset($r["owner_phone"]) ? $r["owner_phone"] : null,
                    "creator" => isset($r["creator"]) ? $r["creator"] : null,
                ];
            } catch (APIException $e) {
                if(!isset($r["force"])) throw new Exception("Owner information is incomplete");
                // nothing - maybe return exception info on missing creator params?
            }
        }

        $creator = isset($r["creator"]) ? $r["creator"] : null; // pls don't hurt me.
        $studio_phone = isset($r["studio_phone"]) ? $r["studio_phone"] : null;

        $this->db->insertStudio($r["studio_name"], $r["studio_type"], $r["studio_street_name"],
            $r["studio_street_nr"], $r["studio_long"], $r["studio_lat"], $r["studio_zip"],
            $r["studio_location"], $studio_phone, $creator, $owner, $into_live);
        return ["response" => "success"];
    }

    protected function showStaged() {
        $r = $this->request;
        parent::verifyKey(2); // moderator and above
        $page = isset($r["page"]) ? $r["page"] : 0;
        $amount = isset($r["results"]) ? $r["results"] : 50;
        return $this->db->showStaged($amount, $page);
    }

    protected function rateStudio() {
        $r = $this->request;
        parent::checkRequest(["score", "studio"]);
        $user = null;
        if (isset($r["username"])) {
            try {
                $this->verifyKey();
                $user = $r["username"];
            } catch (Exception $e) {} // truncate error
        }
        $this->db->rate($r["score"], $r["studio"], $user, $_SERVER['REMOTE_ADDR']);
        return ["status" => "success"];
    }
}