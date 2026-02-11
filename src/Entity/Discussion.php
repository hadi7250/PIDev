<?php

namespace App\Entity;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\DiscussionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiscussionRepository::class)]
#[ORM\Table(name: 'forum_discussion')]
class Discussion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_forum_discussion', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'forum_discussion_title', type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Please enter a discussion title')]
    #[Assert\Length(max: 255, maxMessage: 'Title cannot be longer than {{ limit }} characters')]
    private ?string $title = null;

    #[ORM\Column(name: 'forum_discussion_content', type: 'text')]
    #[Assert\NotBlank(message: 'Please enter discussion content')]
    private ?string $content = null;

    #[ORM\Column(name: 'forum_discussion_author_name', type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Please enter your name')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'Name must be at least {{ limit }} characters', maxMessage: 'Name cannot be longer than {{ limit }} characters')]
    private ?string $authorName = null;

    #[ORM\Column(name: 'forum_discussion_is_pinned', type: 'boolean')]
    private bool $isPinned = false;

    #[ORM\Column(name: 'forum_discussion_is_locked', type: 'boolean')]
    private bool $isLocked = false;

    #[ORM\Column(name: 'forum_discussion_views', type: 'integer')]
    private int $views = 0;

    #[ORM\Column(name: 'forum_discussion_created_at', type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: 'forum_discussion_updated_at', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: 'id_forum_category', referencedColumnName: 'id_forum_category', nullable: true)]
    private ?Category $category = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?User $author = null;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'discussion', cascade: ['persist', 'remove'])]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function isIsPinned(): bool
    {
        return $this->isPinned;
    }

    public function setIsPinned(bool $isPinned): static
    {
        $this->isPinned = $isPinned;
        return $this;
    }

    public function isIsLocked(): bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked): static
    {
        $this->isLocked = $isLocked;
        return $this;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): static
    {
        $this->views = $views;
        return $this;
    }

    public function incrementViews(): static
    {
        $this->views++;
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setDiscussion($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getDiscussion() === $this) {
                $message->setDiscussion(null);
            }
        }

        return $this;
    }

    public function getMessageCount(): int
    {
        return $this->messages->count();
    }

    public function getLastMessage(): ?Message
    {
        $lastMessage = null;
        foreach ($this->messages as $message) {
            if ($lastMessage === null || $message->getCreatedAt() > $lastMessage->getCreatedAt()) {
                $lastMessage = $message;
            }
        }
        return $lastMessage;
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function __toString(): string
    {
        return $this->title ?? 'New Discussion';
    }
}
