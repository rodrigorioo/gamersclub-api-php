<?php

include('init.php');

try {
    $match = $gamersClubAPI->getMatch(4257, 295357);
} catch (\GamersClubAPI\Exceptions\Curl\Curl $e) {
    echo $e->getMessage();
    exit();
} catch (\GamersClubAPI\Exceptions\ParseHTML\ParseHTML $e) {
    echo $e->getMessage();
    exit();
}

var_dump($match);