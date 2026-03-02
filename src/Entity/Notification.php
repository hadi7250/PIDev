<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\Table(name: 'notifications')]
class Notification
{
    public const TYPE_REPLY_TO_DISCUSSION = 'reply_to_discussion';
    public const TYPE_REPLY_TO_MESSAGE = 'reply_to_message';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Notification type is required')]
    #[Assert\Choice(choices: [self::TYPE_REPLY_TO_DISCUSSION, self::TYPE_REPLY_TO_MESSAGE])]
    private ?string $type = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Message is required')]
    private ?string $message = null;

    #[ORM\ManyToOne(targetEntity: ForumDiscussion::class)]
    private ?ForumDiscussion $relatedDiscussion = null;

    #[ORM\ManyToOne(targetEntity: ForumMessage::class)]
    private ?ForumMessage $relatedMessage = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private bool $isRead = false;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getRelatedDiscussion(): ?ForumDiscussion
    {
        return $this->relatedDiscussion;
    }

    public function setRelatedDiscussion(?ForumDiscussion $relatedDiscussion): static
    {
        $this->relatedDiscussion = $relatedDiscussion;

        return $this;
    }

    public function getRelatedMessage(): ?ForumMessage
    {
        return $this->relatedMessage;
    }

    public function setRelatedMessage(?ForumMessage $relatedMessage): static
    {
        $this->relatedMessage = $relatedMessage;

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

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setRead(bool $isRead): static
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function markAsRead(): static
    {
        $this->isRead = true;

        return $this;
    }

    public function markAsUnread(): static
    {
        $this->isRead = false;

        return $this;
    }

    public function getFormattedMessage(): string
    {
        return match($this->type) {
            self::TYPE_REPLY_TO_DISCUSSION => sprintf(
                'Someone replied to your discussion: "%s"',
                $this->relatedDiscussion?->getTitle() ?? 'Unknown Discussion'
            ),
            self::TYPE_REPLY_TO_MESSAGE => sprintf(
                'Someone replied to your message in: "%s"',
                $this->relatedDiscussion?->getTitle() ?? 'Unknown Discussion'
            ),
            default => $this->message,
        };
    }

    public function getActionUrl(): ?string
    {
        if ($this->relatedDiscussion) {
            return '/forum/discussion/' . $this->relatedDiscussion->getId();
        }
        
        return null;
    }

    public function __toString(): string
    {
        return $this->getFormattedMessage();
    }
}
