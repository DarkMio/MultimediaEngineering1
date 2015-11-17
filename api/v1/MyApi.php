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
    protected function verifyKey($x, $origin) {
        echo $x, $origin;
        return true;
    }
    /**
     * Example of an Endpoint
     */
    protected function locations() {
        if ($this->method == 'GET') {
            if(!isset($this->args["offset"])) $this->args["offset"] = "";
            if(!isset($this->args["zip_code"])) $this->args["zip_code"] = "";
            return $this->pull_locations($this->args["offset"], $this->args["zip_code"]); //$this->User->name;
        } else {
            return "Only accepts GET requests";
        }
    }

    protected function verifyLocation() {
        if(!isset($this->request["zip"])) throw new Exception("No zip");
        if(!isset($this->request["location"])) throw new Exception("No location");

        return $this->db->verifyZip($this->request["zip"], $this->request["location"]);
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