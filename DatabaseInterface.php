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
        $query = "SELECT * FROM studios WHERE
                  WHERE abs(geo_long - ?) < 0.5
                  AND abs(geo_lat - ?) < 0.5
                  CROSS JOIN ON adresses
                  WHERE studios.adress == adresses.id
                  CROSS JOIN ON persons
                  WHERE studios.owner == persons.id
                  CROSS JOIN ON studio_types
                  WHERE studios.studio_type == studio_types.id";
        $types = "ii";
        if (isset($offset)) {$query .= " OFFSET ?"; $types .= "i";}
        return $this->__dispatch($query, $types, array(&$long, &$lat, &$offset);
    }


}