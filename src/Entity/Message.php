<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: 'forum_message')]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_forum_message', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'forum_message_content', type: 'text')]
    #[Assert\NotBlank(message: 'Please enter a message')]
    private ?string $content = null;

    #[ORM\Column(name: 'forum_message_author_name', type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Please enter your name')]
    #[Assert\Length(max: 255, maxMessage: 'Name cannot be longer than {{ limit }} characters')]
    private ?string $authorName = null;

    #[ORM\Column(name: 'forum_message_author_email', type: 'string', length: 255, nullable: true)]
    #[Assert\Email(message: 'Please enter a valid email address')]
    private ?string $authorEmail = null;

    #[ORM\Column(name: 'forum_message_is_author', type: 'boolean')]
    private bool $isAuthor = false;

    #[ORM\Column(name: 'forum_message_created_at', type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: 'forum_message_updated_at', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(name: 'id_forum_discussion', referencedColumnName: 'id_forum_discussion', nullable: false)]
    private ?Discussion $discussion = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): static
    {
        $this->authorName = $authorName;
        return $this;
    }

    public function getAuthorEmail(): ?string
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail(?string $authorEmail): static
    {
        $this->authorEmail = $authorEmail;
        return $this;
    }

    public function isIsAuthor(): bool
    {
        return $this->isAuthor;
    }

    public function setIsAuthor(bool $isAuthor): static
    {
        $this->isAuthor = $isAuthor;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getDiscussion(): ?Discussion
    {
        return $this->discussion;
    }

    public function setDiscussion(?Discussion $discussion): static
    {
        $this->discussion = $discussion;
        return $this;
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function __toString(): string
    {
        return substr($this->content ?? '', 0, 50) . '...';
    }
}
