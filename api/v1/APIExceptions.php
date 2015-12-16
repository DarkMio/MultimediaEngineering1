<?php

// TODO: Refactor exceptions


class FailStateException extends \Exception {
    private $_options;
    public function __construct(array $options, $code = 0, Exception $previous = null) {
        parent::__construct("Server exited with a fail state");
        $this->_options = $options;
    }
    public function GetOptions() { return $this->_options; }

}