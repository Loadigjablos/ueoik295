<?php
    /**
     * @param _string the string that will be processed to safety
     * @return _string either false or a correct string
     */
    function validate_string($_string) {
        $_string = addslashes($_string);
        $_string = strip_tags($_string);
        if (!(isset($_string) && !(strlen($_string) < 1) && !(empty($_string)))) {
            return false;
        }
        return $_string;
    }
    /**
     * @param _integer the integer that will be processed to safety
     * @return _integer either false or a correct integer
     */
    function validate_number($_integer) {
        $_integer = intval($_integer);
        return $_integer;
    }
    /**
     * @param _float the float that will be processed to safety
     * @return _float either false or a correct float
     */
    function validate_float($_float) {
        $_float = floatval($_float);
        return $_float;
    }
    /**
     * @param _bool the integer that will be processed to safety
     * @return _bool either true or false
     */
    function validate_boolean($_bool) {
        $_bool = filter_var($_bool, FILTER_VALIDATE_BOOLEAN);
        return $_bool;
    }
    /**
     * prints out a error message and instantly shuts down
     */
    function error_function($status_code, $message) {
        $array = array("error" => $message);
        echo json_encode($array, true);
        http_response_code($status_code);
        die();
    }
    /**
     * prints out a information message and instantly shuts down
     */
    function message_function($status_code, $message) {
        $array = array("information" => $message);
        echo json_encode($array, true);
        http_response_code($status_code);
        die();
    }
    /**
     * validates the token in the cookies if it is valid.
     */
    function validate_token() {
        require_once "Controler/Secret.php";
        if (isset($_COOKIE["token"]) || Token::validate($_COOKIE["token"], $_passwd)) {
            return;
        } else {
            error_function(401, "unotherised");
        }
    }
?>