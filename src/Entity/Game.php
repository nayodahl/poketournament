<?php

namespace App\Entity;

use App\Repository\GameRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class)
     */
    private $winner;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class)
     */
    private $loser;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private $player1;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private $player2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\PositiveOrZero
     */
    private $scorePlayer1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\PositiveOrZero
     */
    private $scorePlayer2;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="games", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    public function __construct()
    {
        $this->createdAt= new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getWinner(): ?Pokemon
    {
        return $this->winner;
    }

    public function setWinner(?Pokemon $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getLoser(): ?Pokemon
    {
        return $this->loser;
    }

    public function setLoser(?Pokemon $loser): self
    {
        $this->loser = $loser;

        return $this;
    }

    public function getPlayer1(): ?Pokemon
    {
        return $this->player1;
    }

    public function setPlayer1(?Pokemon $player1): self
    {
        $this->player1 = $player1;

        return $this;
    }

    public function getPlayer2(): ?Pokemon
    {
        return $this->player2;
    }

    public function setPlayer2(?Pokemon $player2): self
    {
        $this->player2 = $player2;

        return $this;
    }

    public function getScorePlayer1(): ?int
    {
        return $this->scorePlayer1;
    }

    public function setScorePlayer1(?int $scorePlayer1): self
    {
        $this->scorePlayer1 = $scorePlayer1;

        return $this;
    }

    public function getScorePlayer2(): ?int
    {
        return $this->scorePlayer2;
    }

    public function setScorePlayer2(?int $scorePlayer2): self
    {
        $this->scorePlayer2 = $scorePlayer2;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }
}
