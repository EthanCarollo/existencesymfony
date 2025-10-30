<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\BuildingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BuildingsRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
    ]
)]
class Buildings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['grid:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['grid:read'])]
    private ?int $width = null;

    #[ORM\Column]
    #[Groups(['grid:read'])]
    private ?int $height = null;

    #[ORM\Column(length: 255)]
    #[Groups(['grid:read'])]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    #[Groups(['grid:read'])]
    private ?string $image = null;

    #[ORM\Column]
    #[Groups(['grid:read'])]
    private ?int $length = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): static
    {
        $this->length = $length;

        return $this;
    }
}
