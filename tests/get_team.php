<?php

include('init.php');

try {
    $team = $gamersClubAPI->getTeam(148715);
} catch (\GamersClubAPI\Exceptions\Curl\Curl $e) {
    echo $e->getMessage();
    exit();
} catch (\GamersClubAPI\Exceptions\ParseHTML\ParseHTML $e) {
    echo $e->getMessage();
    exit();
}

var_dump($team);