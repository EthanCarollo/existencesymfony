<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
# #[ApiResource]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $personality = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GridBuilding $building = null;

    /**
     * @var Collection<int, Chat>
     */
    #[ORM\OneToMany(targetEntity: Chat::class, mappedBy: 'receiver', orphanRemoval: true)]
    private Collection $receivedChats;

    /**
     * @var Collection<int, Chat>
     */
    #[ORM\OneToMany(targetEntity: Chat::class, mappedBy: 'sended', orphanRemoval: true)]
    private Collection $sendedChats;

    public function __construct()
    {
        $this->receiver = new ArrayCollection();
        $this->receivedChats = new ArrayCollection();
        $this->sendedChats = new ArrayCollection();
    }

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

    public function getPersonality(): ?string
    {
        return $this->personality;
    }

    public function setPersonality(string $personality): static
    {
        $this->personality = $personality;

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

    public function getBuilding(): ?GridBuilding
    {
        return $this->building;
    }

    public function setBuilding(?GridBuilding $building): static
    {
        $this->building = $building;

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getReceivedChats(): Collection
    {
        return $this->receivedChats;
    }

    public function addReceivedChat(Chat $receivedChat): static
    {
        if (!$this->receivedChats->contains($receivedChat)) {
            $this->receivedChats->add($receivedChat);
            $receivedChat->setReceiver($this);
        }

        return $this;
    }

    public function removeReceivedChat(Chat $receivedChat): static
    {
        if ($this->receivedChats->removeElement($receivedChat)) {
            // set the owning side to null (unless already changed)
            if ($receivedChat->getReceiver() === $this) {
                $receivedChat->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getSendedChats(): Collection
    {
        return $this->sendedChats;
    }

    public function addSendedChat(Chat $sendedChat): static
    {
        if (!$this->sendedChats->contains($sendedChat)) {
            $this->sendedChats->add($sendedChat);
            $sendedChat->setSended($this);
        }

        return $this;
    }

    public function removeSendedChat(Chat $sendedChat): static
    {
        if ($this->sendedChats->removeElement($sendedChat)) {
            // set the owning side to null (unless already changed)
            if ($sendedChat->getSended() === $this) {
                $sendedChat->setSended(null);
            }
        }

        return $this;
    }
}
