<?php

namespace GamersClubAPI\HTMLParsers;

use GamersClubAPI\Exceptions\ParseHTML\InvalidHTMLResponse;
use GamersClubAPI\Exceptions\ParseHTML\ResponseHeaderEmpty;
use GamersClubAPI\Interfaces\HTMLParser;

class Match implements HTMLParser {

    public function parseHTML($html) {

        if(!$html || $html == '') {

            throw new InvalidHTMLResponse;

        } else {

            $responseHeaderEventMatch = strstr($html, '<div class="header-event-match">');
            $responseHeaderEventMatch = substr($responseHeaderEventMatch, 0, strpos($responseHeaderEventMatch, '<script id="campeonatoMapTemplate" type="x-tmpl-mustache">'));

            if(!$responseHeaderEventMatch || $responseHeaderEventMatch == '') {

                throw new ResponseHeaderEmpty;

            } else {

                $match = new \GamersClubAPI\Match();

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

                    // IS FINISHED?
                    if($match->getScore1() && $match->getScore2() && !$match->isLive()) {
                        $match->setFinished(true);
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
}