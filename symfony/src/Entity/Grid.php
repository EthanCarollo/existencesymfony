<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\Grid\GetGridController;
use App\Controller\Auth\MeController;
use App\Controller\Auth\RegisterController;
use App\Repository\GridRepository;
use App\State\CurrentGridProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[ORM\Entity(repositoryClass: GridRepository::class)]
#[ApiResource(operations: [
    new Get(uriTemplate: '/grid',
        normalizationContext: [
            'groups' => ['grid:read']
        ],
        name: "grid",
        provider: CurrentGridProvider::class),
    new GetCollection()
])]
class Grid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['grid:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['grid:read'])]
    private ?int $size = null;

    #[ORM\OneToOne(inversedBy: 'grid', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, GridBuilding>
     */
    #[ORM\OneToMany(targetEntity: GridBuilding::class, mappedBy: 'grid', orphanRemoval: true)]
    #[Groups(['grid:read'])]
    private Collection $gridBuildings;


    public function __construct()
    {
        $this->gridBuildings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, GridBuilding>
     */
    public function getGridBuildings(): Collection
    {
        return $this->gridBuildings;
    }

    public function addGridBuilding(GridBuilding $gridBuilding): static
    {
        if (!$this->gridBuildings->contains($gridBuilding)) {
            $this->gridBuildings->add($gridBuilding);
            $gridBuilding->setGrid($this);
        }

        return $this;
    }

    public function removeGridBuilding(GridBuilding $gridBuilding): static
    {
        if ($this->gridBuildings->removeElement($gridBuilding)) {
            // set the owning side to null (unless already changed)
            if ($gridBuilding->getGrid() === $this) {
                $gridBuilding->setGrid(null);
            }
        }

        return $this;
    }
}
