<?php

namespace App\Entity;

use App\Repository\ForumDiscussionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumDiscussionRepository::class)]
#[ORM\Table(name: 'forum_discussion')]
class ForumDiscussion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Title is required')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Title must be at least {{ limit }} characters long',
        maxMessage: 'Title cannot exceed {{ limit }} characters'
    )]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Content is required')]
    #[Assert\Length(
        min: 10,
        minMessage: 'Content must be at least {{ limit }} characters long'
    )]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'discussions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'discussions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumCategory $category = null;

    #[ORM\OneToMany(mappedBy: 'discussion', targetEntity: ForumMessage::class, cascade: ['remove'])]
    private Collection $messages;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attachmentName = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $views = 0;

    // This is not mapped to the database - it's just for file upload
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: [
            'image/jpeg',
            'image/png',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
        mimeTypesMessage: 'Please upload a valid file (JPEG, PNG, PDF, DOC, or DOCX)',
        maxSizeMessage: 'The file is too large. Maximum size is 5MB.'
    )]
    private ?File $attachmentFile = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
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
        $this->updatedAt = new \DateTimeImmutable();

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getCategory(): ?ForumCategory
    {
        return $this->category;
    }

    public function setCategory(?ForumCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, ForumMessage>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(ForumMessage $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setDiscussion($this);
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function removeMessage(ForumMessage $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getDiscussion() === $this) {
                $message->setDiscussion(null);
            }

            $this->messages->removeElement($message);
        }

        return $this;
    }

    public function getMessageCount(): int
    {
        return $this->messages->count();
    }

    public function getLastMessage(): ?ForumMessage
    {
        $lastMessage = null;
        $lastDate = null;

        foreach ($this->messages as $message) {
            if ($lastDate === null || $message->getCreatedAt() > $lastDate) {
                $lastMessage = $message;
                $lastDate = $message->getCreatedAt();
            }
        }

        return $lastMessage;
    }

    public function getAttachmentName(): ?string
    {
        return $this->attachmentName;
    }

    public function setAttachmentName(?string $attachmentName): static
    {
        $this->attachmentName = $attachmentName;

        return $this;
    }

    public function getAttachmentFile(): ?File
    {
        return $this->attachmentFile;
    }

    public function setAttachmentFile(?File $attachmentFile): static
    {
        $this->attachmentFile = $attachmentFile;

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

    public function isImage(): bool
    {
        if (!$this->attachmentName) {
            return false;
        }

        $extension = strtolower(pathinfo($this->attachmentName, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
    }

    public function getOriginalFilename(): string
    {
        if (!$this->attachmentName) {
            return '';
        }

        // For now, just return the stored filename
        // This prevents the array_pop error
        return $this->attachmentName;
    }

    public function __toString(): string
    {
        return $this->title ?? 'New Discussion';
    }
}
