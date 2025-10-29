<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'receiver')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $sender = null;

    #[ORM\ManyToOne(inversedBy: 'receivedChats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $receiver = null;

    #[ORM\ManyToOne(inversedBy: 'sendedChats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $sended = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?Character
    {
        return $this->sender;
    }

    public function setSender(?Character $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?Character
    {
        return $this->receiver;
    }

    public function setReceiver(?Character $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
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
