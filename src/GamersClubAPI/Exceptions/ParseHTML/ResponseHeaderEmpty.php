<?php

namespace GamersClubAPI\Exceptions\ParseHTML;

class ResponseHeaderEmpty extends ParseHTML {

    public function __construct($message = 'Response Header Empty', $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}