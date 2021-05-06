<?php

include('init.php');

try {
    $teamMatches = $gamersClubAPI->getTeamMatches(217671);
} catch (\GamersClubAPI\Exceptions\Curl\Curl $e) {
    echo $e->getMessage();
    exit();
} catch (\GamersClubAPI\Exceptions\ParseHTML\ParseHTML $e) {
    echo $e->getMessage();
    exit();
}

var_dump($teamMatches);