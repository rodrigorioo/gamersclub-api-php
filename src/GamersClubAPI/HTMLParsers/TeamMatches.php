<?php

namespace GamersClubAPI\HTMLParsers;

use GamersClubAPI\Exceptions\ParseHTML\InvalidHTMLResponse;
use GamersClubAPI\Exceptions\ParseHTML\ResponseHeaderEmpty;
use GamersClubAPI\Interfaces\HTMLParser;
use GamersClubAPI\Match;
use GamersClubAPI\Tournament;

class TeamMatches implements HTMLParser {


    public function parseHTML($html) {
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