<?php

namespace GamersClubAPI\Exceptions\ParseHTML;

class InvalidType extends ParseHTML {

    public function __construct($message = 'Invalid type', $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}