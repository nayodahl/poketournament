<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberPokemons;

    /**
     * @ORM\ManyToMany(targetEntity=Pokemon::class, inversedBy="tournaments")
     */
    private $Pokemons;

    public function __construct()
    {
        $this->Pokemons = new ArrayCollection();
    }


    public function getId(): ?int
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
     * @return Collection|Pokemon[]
     */
    public function getPokemons(): Collection
    {
        return $this->Pokemons;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->Pokemons->contains($pokemon)) {
            $this->Pokemons[] = $pokemon;
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        $this->Pokemons->removeElement($pokemon);

        return $this;
    }
}
