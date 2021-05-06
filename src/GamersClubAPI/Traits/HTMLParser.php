<?php

namespace GamersClubAPI\Traits;

use GamersClubAPI\Exceptions\ParseHTML\InvalidHTMLResponse;
use GamersClubAPI\Exceptions\ParseHTML\InvalidType;
use GamersClubAPI\Exceptions\ParseHTML\ResponseHeaderEmpty;
use GamersClubAPI\Match;
use GamersClubAPI\Player;
use GamersClubAPI\Team;
use GamersClubAPI\Tournament;

trait HTMLParser {

    public function parseHTML ($html, $type) {

        switch($type) {

            case 'match':

                return $this->parseHTMLMatch($html);
                break;

            case 'team':

                return $this->parseHTMLTeam($html);
                break;

            case 'team_matches':

                return $this->parseHTMLTeamMatches($html);
                break;

            default:

                throw new InvalidType;

                break;
        }
    }

    public function parseHTMLMatch($html) {

        if(!$html || $html == '') {

            throw new InvalidHTMLResponse;

        } else {

            $responseHeaderEventMatch = strstr($html, '<div class="header-event-match">');
            $responseHeaderEventMatch = substr($responseHeaderEventMatch, 0, strpos($responseHeaderEventMatch, '<script id="campeonatoMapTemplate" type="x-tmpl-mustache">'));

            if(!$responseHeaderEventMatch || $responseHeaderEventMatch == '') {

                throw new ResponseHeaderEmpty;

            } else {

                $match = new Match();

                $domHTML = new \DOMDocument();
                libxml_use_internal_errors(true);
                $domHTML->loadHTML($responseHeaderEventMatch);

                $domDivs = $domHTML->getElementsByTagName('div');
                foreach ($domDivs as $domDiv) {

                    $class = $domDiv->getAttribute('class');
                    $id = $domDiv->getAttribute('id');

                    // ID TOURNAMENT
                    if($class == 'row buttons-event-match') {
                        $a = $domDiv->getElementsByTagName('a')[0];
                        $aHref = explode('/', $a->getAttribute('href'));
                        $idTournament = $aHref[3];

                        $match->getTournament()->setId($idTournament);
                    }

                    // TOURNAMENT NAME
                    if($class == 'event-cover') {
                        $img = $domDiv->getElementsByTagName('img')[0];
                        $title = $img->getAttribute('title');

                        $match->getTournament()->setName($title);
                    }

                    // IS LIVE?
                    if ($class == 'live') {
                        $match->setLive(true);
                    }

                    // RESULTS
                    if($class == 'match-result') {

                        $span = $domDiv->getElementsByTagName('span')[0];
                        $result = $span->nodeValue;

                        if($id == 'matchscore1') {
                            $match->setScore1($result);
                        }

                        if($id == 'matchscore2') {
                            $match->setScore2($result);
                        }
                    }

                    // BEST OF
                    if($class == 'best-of') {
                        $best_of = trim(preg_replace('/\s\s+/', ' ', $domDiv->nodeValue));

                        $match->setBestOf($best_of);
                    }

                    // DATE
                    if($class == 'date') {
                        $date = trim(preg_replace('/\s\s+/', ' ', $domDiv->nodeValue));

                        $match->setDate($date);
                    }

                    // HOUR
                    if($class == 'time') {
                        $time = trim(preg_replace('/\s\s+/', ' ', $domDiv->nodeValue));

                        $match->setHour($time);
                    }

                    // MAPS
                    if($class == 'map') {
                        $maps = trim(preg_replace('/\s\s+/', ' ', $domDiv->nodeValue));

                        $match->setMaps($maps);
                    }
                }

                // TEAM NAMES
                $domH4s = $domHTML->getElementsByTagName('h4');
                foreach($domH4s as $iDomH4 => $domH4) {
                    $class = $domH4->getAttribute('class');
                    $id = $domH4->getAttribute('id');

                    if($class == 'team-name') {
                        $small = $domH4->getElementsByTagName('small')[0];
                        $smallValue = trim(preg_replace('/\s\s+/', ' ', $small->nodeValue));

                        $teamName = trim(preg_replace('/\s\s+/', ' ', str_replace($smallValue, '', $domH4->nodeValue)));

                        if($iDomH4) {
                            $match->setTeam1($teamName);
                        } else {
                            $match->setTeam2($teamName);
                        }

                    }
                }

                return $match;
            }
        }
    }

    public function parseHTMLTeam($html) {

        if(!$html || $html == '') {

            throw new InvalidHTMLResponse;

        } else {

            $responseHeaderEventMatch = strstr($html, '<div class="TeamProfile">');
            $responseHeaderEventMatch = substr($responseHeaderEventMatch, 0, strpos($responseHeaderEventMatch, '<div id="modal-join-clan-container">'));

            if(!$responseHeaderEventMatch || $responseHeaderEventMatch == '') {

                throw new ResponseHeaderEmpty;

            } else {

                $team = new Team();

                $domHTML = new \DOMDocument();
                libxml_use_internal_errors(true);
                $domHTML->loadHTML($responseHeaderEventMatch);

                $domDivs = $domHTML->getElementsByTagName('div');
                foreach ($domDivs as $domDiv) {

                    $class = $domDiv->getAttribute('class');
                    $id = $domDiv->getAttribute('id');

                    // LOGO
                    if($class == 'TeamProfile__avatar') {
                        $img = $domDiv->getElementsByTagName('img')[0];
                        $urlImg = $img->getAttribute('src');

                        $team->setLogo($urlImg);
                    }

                    // PLAYERS
                    if (strpos($class, 'TeamProfile__roster__player PlayerCard PlayerCard--vertical') !== false) {

                        $player = new Player();

                        $playerDivs = $domDiv->getElementsByTagName('div');
                        foreach($playerDivs as $playerDiv) {

                            $classPlayerDiv = $playerDiv->getAttribute('class');
                            $id = $playerDiv->getAttribute('id');

                            if($classPlayerDiv == 'PlayerCard__avatar__imgContainer') {
                                $a = $playerDiv->getElementsByTagName('a')[0];

                                $urlA = explode('/', $a->getAttribute('href'));
                                $player->setId($urlA[2]);

                                $img = $playerDiv->getElementsByTagName('img')[0];
                                $urlAvatar = $img->getAttribute('src');
                                $player->setAvatar($urlAvatar);
                            }

                            if($classPlayerDiv == 'PlayerCard__meta__item') {
                                $spans = $playerDiv->getElementsByTagName('span');

                                foreach($spans as $span) {

                                    $classSpan = $span->getAttribute('class');

                                    if($classSpan == 'badge-level-value') {
                                        $player->setLevel($span->nodeValue);
                                    }
                                }
                            }
                        }

                        $h3Name = $domDiv->getElementsByTagName('h3')[0];
                        $span = $h3Name->getElementsByTagName('span')[0];
                        $player->setName($span->nodeValue);

                        $team->addPlayer($player);
                    }
                }

                $h1Divs = $domHTML->getElementsByTagName('h1');
                foreach($h1Divs as $h1Div) {
                    $class = $h1Div->getAttribute('class');
                    $id = $h1Div->getAttribute('id');

                    // NAME
                    if($class == 'TeamProfile__name') {
                        $span = $h1Div->getElementsByTagName('span')[0];

                        $team->setName($span->nodeValue);
                    }

                }

                $h2Divs = $domHTML->getElementsByTagName('h2');
                foreach($h2Divs as $h2Div) {
                    $class = $h2Div->getAttribute('class');
                    $id = $h2Div->getAttribute('id');

                    // TAG
                    if($class == 'TeamProfile__tag') {
                        $team->setTag($h2Div->nodeValue);
                    }

                }

                return $team;
            }
        }
    }

    public function parseHTMLTeamMatches($html) {

        if(!$html || $html == '') {

            throw new InvalidHTMLResponse;

        } else {

            $responseHeaderEventMatch = strstr($html, '<div class="internal-page team-management">');
            $responseHeaderEventMatch = substr($responseHeaderEventMatch, 0, strpos($responseHeaderEventMatch, '<footer class="footer" id="footer">'));

            if(!$responseHeaderEventMatch || $responseHeaderEventMatch == '') {

                throw new ResponseHeaderEmpty;

            } else {

                $matches = [];

                $domHTML = new \DOMDocument();
                libxml_use_internal_errors(true);
                $domHTML->loadHTML($responseHeaderEventMatch);

                $domDivs = $domHTML->getElementsByTagName('div');
                foreach ($domDivs as $domDiv) {

                    $class = $domDiv->getAttribute('class');
                    $id = $domDiv->getAttribute('id');

                    // TABLA OF MATCHES
                    if ($class == 'table-gc table-responsive team-table-gc') {

                        $table = $domDiv->getElementsByTagName('table')[0];
                        $tbody = $table->getElementsByTagName('tbody')[0];
                        $trs = $tbody->getElementsByTagName('tr');

                        foreach($trs as $tr) {

                            $match = new Match();

                            $tds = $tr->getElementsByTagName('td');

                            foreach($tds as $iTd => $td) {

                                switch($iTd) {

                                    // DATE
                                    case 0:

                                        $a = $td->getElementsByTagName('a')[0];
                                        $aClass = $a->getAttribute('class');
                                        $aHref = $a->getAttribute('href');

                                        $idMatch = explode('/', $aHref)[5];

                                        $match->setId($idMatch);

                                        $dateString = $a->nodeValue;


                                        if(strpos($dateString, 'HOJE') !== false) { // THE MATCH IS TODAY

                                            $date = date('d/m/Y');
                                            $hour = trim(preg_replace('/\s\s+/', ' ', str_replace('HOJE ', '', $dateString)));

                                            $match->setDate($date);
                                            $match->setHour($hour);
                                        } else if(strpos($dateString, 'LIVE') !== false) { // THE MATCH IS NOW

                                            $date = date('d/m/Y');
                                            $hour = trim(preg_replace('/\s\s+/', ' ', str_replace('LIVE ', '', $dateString)));

                                            $match->setDate($date);
                                            $match->setHour($hour);
                                            $match->setLive(true);
                                        } else if (strpos($dateString, 'TBA') !== false) { // THE MATCH IS TBA

                                            //

                                        } else { // THE MATCH IS OTHER DAY

                                            $dateString = rtrim(ltrim(preg_replace('/\s\s+/', ' ', $dateString)));

                                            $explodeDateString = explode(' ', $dateString);
                                            $date = $explodeDateString[0];
                                            $hour = $explodeDateString[1];

                                            $match->setDate($date);
                                            $match->setHour($hour);
                                        }

                                        // IS FINISHED?
                                        if(strpos($aClass, 'finished') !== false) {
                                            $match->setFinished(true);
                                        }



                                        break;

                                    case 1:

                                        $a = $td->getElementsByTagName('a')[0];
                                        $teamName = trim(preg_replace('/\s\s+/', ' ', $a->nodeValue));

                                        $match->setTeam1($teamName);
                                        break;

                                    case 2:

                                        $span = $td->getElementsByTagName('span')[0];
                                        $score = trim(preg_replace('/\s\s+/', ' ', $span->nodeValue));

                                        $match->setScore1($score);
                                        break;

                                    case 3:

                                        $span = $td->getElementsByTagName('span')[0];
                                        $score = trim(preg_replace('/\s\s+/', ' ', $span->nodeValue));

                                        $match->setScore2($score);

                                        break;

                                    case 4:

                                        $a = $td->getElementsByTagName('a')[0];
                                        $teamName = trim(preg_replace('/\s\s+/', ' ', $a->nodeValue));

                                        $match->setTeam2($teamName);
                                        break;

                                    case 5:

                                        $a = $td->getElementsByTagName('a')[0];
                                        $aHref = $a->getAttribute('href');

                                        $idTournament = explode('/', $aHref)[3];
                                        $tournamentName = trim(preg_replace('/\s\s+/', ' ', $a->nodeValue));

                                        $tournament = new Tournament();
                                        $tournament->setId($idTournament);
                                        $tournament->setName($tournamentName);

                                        $match->setTournament($tournament);

                                        break;
                                }
                            }

                            $matches[] = $match;
                        }
                    }
                }

                return $matches;
            }
        }
    }


}