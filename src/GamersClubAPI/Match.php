<?php

namespace GamersClubAPI;

class Match {

    private string $id = "";
    private Tournament $tournament;
    private bool $live = false;
    private string $team1 = "";
    private string $team2 = "";
    private $score1 = null;
    private $score2 = null;
    private bool $finished = false;
    private string $best_of = "";
    private string $date = "";
    private string $hour = "";
    private string $maps = "";

    public function __construct () {
        $this->setTournament(new Tournament());
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Tournament
     */
    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    /**
     * @param Tournament $tournament
     */
    public function setTournament(Tournament $tournament): void
    {
        $this->tournament = $tournament;
    }

    /**
     * @return mixed
     */
    public function getLive()
    {
        return $this->live;
    }

    /**
     * @param mixed $live
     */
    public function setLive($live): void
    {
        $this->live = $live;
    }

    /**
     * @return null
     */
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * @param null $team1
     */
    public function setTeam1($team1): void
    {
        $this->team1 = $team1;
    }

    /**
     * @return null
     */
    public function getTeam2()
    {
        return $this->team2;
    }

    /**
     * @param null $team2
     */
    public function setTeam2($team2): void
    {
        $this->team2 = $team2;
    }

    /**
     * @return mixed
     */
    public function getScore1()
    {
        return $this->score1;
    }

    /**
     * @param mixed $result1
     */
    public function setScore1($score1): void
    {
        $this->score1 = $score1;
    }

    /**
     * @return mixed
     */
    public function getScore2()
    {
        return $this->score2;
    }

    /**
     * @param mixed $result2
     */
    public function setScore2($score2): void
    {
        $this->score2 = $score2;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->finished;
    }

    /**
     * @param bool $finished
     */
    public function setFinished(bool $finished): void
    {
        $this->finished = $finished;
    }

    /**
     * @return mixed
     */
    public function getBestOf()
    {
        return $this->best_of;
    }

    /**
     * @param mixed $best_of
     */
    public function setBestOf($best_of): void
    {
        switch($best_of) {
            case 'Melhor de 3':
                $best_of = 'BO3';
                break;

            case 'Melhor de 1':
                $best_of = 'BO1';
                break;
        }

        $this->best_of = $best_of;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param mixed $hour
     */
    public function setHour($hour): void
    {
        $this->hour = $hour;
    }

    /**
     * @return mixed
     */
    public function Scores()
    {
        return $this->maps;
    }

    /**
     * @param mixed $maps
     */
    public function setMaps($maps): void
    {
        $this->maps = $maps;
    }
}