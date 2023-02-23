<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
// #[Broadcast]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages', fetch: 'EAGER')]
    private ?Conversation $conversation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $content = null;
    
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $readAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $from_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $to_id = null;

    //from_id

    //to_id

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    
    public function getReadAt(): ?\DateTimeImmutable
    {
        return $this->readAt;
    }

    public function setReadAt(?\DateTimeImmutable $readAt): self
    {
        $this->readAt = $readAt;

        return $this;
    }

    public function getFromId(): ?int
    {
        return $this->from_id;
    }

    public function setFromId(?int $from_id): self
    {
        $this->from_id = $from_id;

        return $this;
    }

    public function getToId(): ?int
    {
        return $this->to_id;
    }

    public function setToId(?int $to_id): self
    {
        $this->to_id = $to_id;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable());
        }
        $this->setUpdatedAt(new DateTimeImmutable());
    }
}
