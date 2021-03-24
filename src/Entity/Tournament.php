<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TournamentRepository::class)
 */
class Tournament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $date;

    /**
     * @ORM\Column(type="integer")
     */
    private int $numberPokemons;

    /**
     * @ORM\ManyToMany(targetEntity=Pokemon::class, inversedBy="tournaments")
     * @ORM\JoinTable(name="tournaments_pokemons")
     * @var Collection<int, Pokemon>
     */
    private Collection $pokemons;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="tournament", orphanRemoval=true)
     * @var Collection<int, Game>
     */
    private Collection $games;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
        $this->games = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNumberPokemons(): ?int
    {
        return $this->numberPokemons;
    }

    public function setNumberPokemons(int $numberPokemons): self
    {
        $this->numberPokemons = $numberPokemons;

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>|Pokemon[]
     */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons[] = $pokemon;
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        $this->pokemons->removeElement($pokemon);

        return $this;
    }

    /**
     * @return Collection<int, Game>|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setTournament($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTournament() === $this) {
                $game->setTournament(null);
            }
        }

        return $this;
    }
}
