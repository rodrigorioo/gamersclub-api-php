<?php

namespace GamersClubAPI;

class Team {

    private string $id = "";
    private $logo = null;
    private string $name = "";
    private string $tag = "";

    private array $players = [];
    private array $matches = [];

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
     * @return null
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param null $logo
     */
    public function setLogo($logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return array
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * @param array $players
     */
    public function setPlayers(array $players): void
    {
        $this->players = $players;
    }

    /**
     * @return array
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * @param array $matches
     */
    public function setMatches(array $matches): void
    {
        $this->matches = $matches;
    }

    public function addPlayer(Player $player): void {
        $this->players[] = $player;
    }

    public function addMatch(Match $match): void {
        $this->matches[] = $match;
    }
}