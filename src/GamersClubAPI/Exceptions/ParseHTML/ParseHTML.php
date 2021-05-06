<?php

namespace GamersClubAPI\Exceptions\ParseHTML;

class ParseHTML extends \Exception {

    public function __construct($message = 'Parse HTML Exception', $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}