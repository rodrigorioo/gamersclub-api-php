<?php

namespace GamersClubAPI;

use GamersClubAPI\Exceptions\ParseHTML\ParseHTML;
use GamersClubAPI\Traits\Curl;
use GamersClubAPI\Traits\HTMLParser;
use GamersClubAPI\Exceptions\Curl\Curl as CurlException;

class GC {

    use HTMLParser, Curl;

    protected $url;
    protected $sessionId;

    public function __construct ($sessionId, $url = 'https://csgo.gamersclub.gg/') {
        $this->sessionId = $sessionId;
        $this->url = $url;
    }

    public function getMatch($tournamentId, $matchId) {

        try {
            $responseHTML = $this->execCurl('campeonatos/csgo/' . $tournamentId . '/partida/' . $matchId);
        } catch(CurlException $e) {
            throw $e;
        }

        try {
            $data = $this->parseHTML($responseHTML, 'match');
        } catch(ParseHTML $e) {
            throw $e;
        }

        $data->setId($matchId);

        return $data;
    }

    public function getTeam($teamId) {

        try {
            $responseHTML = $this->execCurl('time/' . $teamId);
        } catch(CurlException $e) {
            throw $e;
        }

        try {
            $data = $this->parseHTML($responseHTML, 'team');
        } catch(ParseHTML $e) {
            throw $e;
        }

        $data->setId($teamId);

        return $data;
    }

    public function getTeamMatches($teamId) {

        try {
            $responseHTML = $this->execCurl('team/matches/' . $teamId);
        } catch(CurlException $e) {
            throw $e;
        }

        try {
            $data = $this->parseHTML($responseHTML, 'team_matches');
        } catch(ParseHTML $e) {
            throw $e;
        }

        return $data;
    }

    /**
     * @return mixed|string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param mixed|string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param mixed $sessionId
     */
    public function setSessionId($sessionId): void
    {
        $this->sessionId = $sessionId;
    }


}