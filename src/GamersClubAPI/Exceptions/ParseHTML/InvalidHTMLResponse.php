<?php

namespace GamersClubAPI\Exceptions\ParseHTML;

class InvalidHTMLResponse extends ParseHTML {

    public function __construct($message = 'Invalid HTML Response', $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}