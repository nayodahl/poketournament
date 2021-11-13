<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass=PokemonRepository::class)
 * @UniqueEntity("apiId")
 * @UniqueEntity("slug")
 */
class Pokemon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Merci d'entrer un nom")
     * @Groups({"list_pokemon"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $color;

    /**
     * @ORM\Column(type="integer")
     */
    private int $apiId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $slug;

    /**
     * @ORM\ManyToMany(targetEntity=Tournament::class, mappedBy="pokemons")
     * @var Collection<int, Tournament>
     */
    private Collection $tournaments;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private int $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private int $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private int $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Pokemon")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private ?Pokemon $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Pokemon", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private ?Pokemon $parent;

    /**
     * @ORM\OneToMany(targetEntity="Pokemon", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     * @var Collection<int, Pokemon>
     */
    private Collection $children;

    /**
     * @var \DateTime $updated_at
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
    */
    private $updated_at;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Type $type1;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Type $type2;

    /**
     * @ORM\ManyToOne(targetEntity=Generation::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Generation $generation;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isLegendary;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isMythical;

    /**
     * @ORM\Column(type="integer")
     */
    private int $height;

    /**
     * @ORM\Column(type="integer")
     */
    private int $weight;


    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getApiId(): ?int
    {
        return $this->apiId;
    }

    public function setApiId(int $apiId): self
    {
        $this->apiId = $apiId;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Tournament>|Tournament[]
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $tournament): self
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments[] = $tournament;
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): self
    {
        $this->tournaments->removeElement($tournament);

        return $this;
    }

    public function getRoot(): ?Pokemon
    {
        return $this->root;
    }

    public function setParent(Pokemon $parent = null): void
    {
        $this->parent = $parent;
    }

    public function getParent(): ?Pokemon
    {
        return $this->parent;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType1(): ?Type
    {
        return $this->type1;
    }

    public function setType1(?Type $type1): self
    {
        $this->type1 = $type1;

        return $this;
    }

    public function getType2(): ?Type
    {
        return $this->type2;
    }

    public function setType2(?Type $type2): self
    {
        $this->type2 = $type2;

        return $this;
    }

    public function getGeneration(): ?Generation
    {
        return $this->generation;
    }

    public function setGeneration(?Generation $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function isLegendary(): ?bool
    {
        return $this->isLegendary;
    }

    public function setLegendary(bool $isLegendary): self
    {
        $this->isLegendary = $isLegendary;

        return $this;
    }

    public function isMythical(): ?bool
    {
        return $this->isMythical;
    }

    public function setMythical(bool $isMythical): self
    {
        $this->isMythical = $isMythical;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}
