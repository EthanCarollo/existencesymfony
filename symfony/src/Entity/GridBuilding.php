<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Dto\GridBuildingInput;
use App\Repository\GridBuildingRepository;
use App\State\GridBuildingPostProcessor;
use App\State\GridBuildingProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GridBuildingRepository::class)]
#[ApiResource(operations: [
    new GetCollection(provider: GridBuildingProvider::class),
    new Post(
        denormalizationContext: [
            'groups' => ['grid_building:write'],
        ],
        input: GridBuildingInput::class,
        processor: GridBuildingPostProcessor::class
    ),
])]
class GridBuilding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['grid:read'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['grid:read'])]
    private ?int $xPos = null;

    #[ORM\Column]
    #[Groups(['grid:read'])]
    private ?int $yPos = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['grid:read'])]
    private ?Buildings $building = null;

    #[Groups(['grid:read'])]
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'building', orphanRemoval: true)]
    private Collection $characters;

    #[ORM\ManyToOne(inversedBy: 'gridBuildings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grid $grid = null;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getXPos(): ?int
    {
        return $this->xPos;
    }

    public function setXPos(?int $xPos): static
    {
        $this->xPos = $xPos;

        return $this;
    }

    public function getYPos(): ?int
    {
        return $this->yPos;
    }

    public function setYPos(int $yPos): static
    {
        $this->yPos = $yPos;

        return $this;
    }

    public function getBuilding(): ?Buildings
    {
        return $this->building;
    }

    public function setBuilding(?Buildings $building): static
    {
        $this->building = $building;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): static
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->setBuilding($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): static
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getBuilding() === $this) {
                $character->setBuilding(null);
            }
        }

        return $this;
    }

    public function getGrid(): ?Grid
    {
        return $this->grid;
    }

    public function setGrid(?Grid $grid): static
    {
        $this->grid = $grid;

        return $this;
    }
}
