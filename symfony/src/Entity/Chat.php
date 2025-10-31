<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['grid:read', 'character:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'receivedChats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $receiver = null;

    #[ORM\ManyToOne(inversedBy: 'sendedChats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $sended = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['grid:read', 'character:read'])]
    private ?string $message = null;

    #[Groups(['grid:read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceiver(): ?Character
    {
        return $this->receiver;
    }

    #[Groups(['character:read', 'grid:read'])]
    public function getReceiverId(): ?int
    {
        return $this->receiver?->getId();
    }


    public function setReceiver(?Character $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    #[Groups(['character:read', 'grid:read'])]
    public function getSendedId(): ?int
    {
        return $this->sended?->getId();
    }

    public function getSended(): ?Character
    {
        return $this->sended;
    }

    public function setSended(?Character $sended): static
    {
        $this->sended = $sended;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
