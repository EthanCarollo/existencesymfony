<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CharacterRepository;
use App\State\CurrentCharacterProvider;
use App\State\GridBuildingProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ApiResource(operations: [
    new GetCollection(normalizationContext: [
        'groups' => ['character:read']
    ],
        provider: CurrentCharacterProvider::class,)
])]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['grid:read', 'character:read'])]
    private ?int $id = null;

    #[Groups(['grid:read', 'character:read'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['character:read', 'grid:read'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $personality = null;

    #[Groups(['grid:read', 'character:read'])]
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[Groups(['grid:read'])]
    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GridBuilding $building = null;

    /**
     * @var Collection<int, Chat>
     */
    #[Groups(['character:read', 'grid:read'])]
    #[ORM\OneToMany(targetEntity: Chat::class, mappedBy: 'receiver', orphanRemoval: true)]
    private Collection $receivedChats;

    /**
     * @var Collection<int, Chat>
     */
    #[Groups(['character:read', 'grid:read'])]
    #[ORM\OneToMany(targetEntity: Chat::class, mappedBy: 'sended', orphanRemoval: true)]
    private Collection $sendedChats;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $personality_prompt = null;

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

        if ($this->image === null) {
            $seed = urlencode($name);
            $this->image = "https://api.dicebear.com/9.x/croodles/svg?seed={$seed}";
        }

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

    public function getPersonalityPrompt(): ?string
    {
        return $this->personality_prompt;
    }

    public function setPersonalityPrompt(string $personality_prompt): static
    {
        $this->personality_prompt = $personality_prompt;

        return $this;
    }
}
