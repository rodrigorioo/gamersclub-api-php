<?php

namespace GamersClubAPI\Exceptions\Curl;

class Curl extends \Exception {
    public function __construct($message = 'Curl Error', $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}