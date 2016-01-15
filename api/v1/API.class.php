<?php
ini_set('memory_limit', '-1');

abstract class API
{
    /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method = '';
    /**
     * Property: endpoint
     * The Model requested in the URI. eg: /files
     */
    protected $endpoint = '';
    /**
     * Property: verb
     * An optional additional descriptor about the endpoint, used for things that can
     * not be handled by the basic methods. eg: /files/process
     */
    protected $verb = '';
    /**
     * Property: args
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    protected $args = Array();
    /**
     * Property: file
     * Stores the input of the PUT request
     */
    protected $file = Null;

    /**
     * Property: db
     * @var DatabaseInterface
     * Is a "permanent" connection to the database.
     */
    protected $db = null;
    /**
     * Constructor: __construct
     * Allow for CORS, assemble and pre-process the data
     */
    public function __construct($request) {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->args = explode('/', rtrim($request, '/'));
        $this->endpoint = array_shift($this->args);
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }

        switch($this->method) {
            case 'DELETE':
            case 'POST':
                $this->request = $this->_cleanInputs($_POST);
                break;
            case 'GET':
                $this->request = $this->_cleanInputs($_GET);
                break;
            case 'PUT':
                $this->request = $this->_cleanInputs($_GET);
                $this->file = file_get_contents("php://input");
                break;
            default:
                $this->_response('Invalid Method', 405);
                break;
        }

        $this->db = new DatabaseInterface("localhost", "root", "1337s1mpl3x", "tattooliste");
    }

    public function processAPI() {
        if (method_exists($this, $this->endpoint)) {
            try {
                return $this->_response($this->{$this->endpoint}($this->args));
            } catch (FailStateException $e) {
                return $this->_response($e->GetOptions(), 200);
            } catch (APIException $e) {
              return $this->_response($e->getOptions(), 400);
            } catch (Exception $e) {
                return $this->_response($e->getMessage(), 405);
            }
        }
        return $this->_response("No Endpoint: $this->endpoint", 404);
    }

    private function _response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode(["request" => $this->request,
                            "status" => $status,
                            "api" => "v1",
                            "response" => $data]);
    }

    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }

    private function _requestStatus($code) {
        $status = array(
            200 => 'OK',
            400 => 'Bad Request',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );

        return ($status[$code]) ? $status[$code] : $status[500];
    }

    public function checkRequest($keys) {
        $dict = ["missing_parameters" => []];
        $critical_error = false;
        foreach($keys as $key){
            if(!isset($this->request[$key])) {
                $dict["missing_parameters"][] = $key;
                $critical_error = true;
            }
        }
        if ($critical_error) throw new APIException($dict);
    }

    public function verifyKey($role=null) {
        try {
            $this->checkRequest(["token", "username"]);
        } catch (Exception $e) {
            // if there are not enough params - we want to throw actually a different error.
            throw new Exception("Insufficient permissions for this API end node.");
        }
        return $this->db->verifyKey($this->request["token"], $this->request["username"], $role);
    }

    public function verifyCaptcha($captcha) {
        $secret = trim(file("secret.txt")[0]);                                                              // read secret key file first line (mostly bc of git)
        $response =                                                                                         // json decode of google api
            json_decode(
                file_get_contents(
                    "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" .
                    $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
        if ($response['success'] == false) {                                                                // if verification failed
            throw new Exception("User captcha is invalid!");
        }
    }
}

class APIException extends \Exception
{

    private $_options;

    public function __construct(array $options,
                                $code = 0,
                                Exception $previous = null)
    {
        parent::__construct("API Exception", $code, $previous);

        $this->_options = $options;
    }

    public function GetOptions() { return $this->_options; }
}