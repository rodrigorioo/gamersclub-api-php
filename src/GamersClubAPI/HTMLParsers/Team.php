<?php

namespace GamersClubAPI\HTMLParsers;

use GamersClubAPI\Exceptions\ParseHTML\InvalidHTMLResponse;
use GamersClubAPI\Exceptions\ParseHTML\ResponseHeaderEmpty;
use GamersClubAPI\Interfaces\HTMLParser;
use GamersClubAPI\Player;

class Team implements HTMLParser {

    public function parseHTML($html) {
        if(!$html || $html == '') {

            throw new InvalidHTMLResponse;

        } else {

            $responseHeaderEventMatch = strstr($html, '<div class="TeamProfile">');
            $responseHeaderEventMatch = substr($responseHeaderEventMatch, 0, strpos($responseHeaderEventMatch, '<div id="modal-join-clan-container">'));

            if(!$responseHeaderEventMatch || $responseHeaderEventMatch == '') {

                throw new ResponseHeaderEmpty;

            } else {

                $team = new \GamersClubAPI\Team();

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
}