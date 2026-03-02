<?php

namespace App\Entity;

use App\Repository\ForumMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumMessageRepository::class)]
#[ORM\Table(name: 'forum_message')]
class ForumMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Message content is required')]
    #[Assert\Length(
        min: 5,
        minMessage: 'Message must be at least {{ limit }} characters long'
    )]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private int $upvotes = 0;

    #[ORM\Column]
    private int $downvotes = 0;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumDiscussion $discussion = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'replies')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?ForumMessage $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class, cascade: ['remove'])]
    private Collection $replies;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->replies = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpvotes(): int
    {
        return $this->upvotes;
    }

    public function setUpvotes(int $upvotes): static
    {
        $this->upvotes = $upvotes;

        return $this;
    }

    public function incrementUpvotes(): static
    {
        $this->upvotes++;

        return $this;
    }

    public function decrementUpvotes(): static
    {
        if ($this->upvotes > 0) {
            $this->upvotes--;
        }

        return $this;
    }

    public function getDownvotes(): int
    {
        return $this->downvotes;
    }

    public function setDownvotes(int $downvotes): static
    {
        $this->downvotes = $downvotes;

        return $this;
    }

    public function incrementDownvotes(): static
    {
        $this->downvotes++;

        return $this;
    }

    public function decrementDownvotes(): static
    {
        if ($this->downvotes > 0) {
            $this->downvotes--;
        }

        return $this;
    }

    public function getScore(): int
    {
        return $this->upvotes - $this->downvotes;
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

    public function getDiscussion(): ?ForumDiscussion
    {
        return $this->discussion;
    }

    public function setDiscussion(?ForumDiscussion $discussion): static
    {
        $this->discussion = $discussion;

        return $this;
    }

    public function getParent(): ?ForumMessage
    {
        return $this->parent;
    }

    public function setParent(?ForumMessage $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, ForumMessage>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(ForumMessage $reply): static
    {
        if (!$this->replies->contains($reply)) {
            $this->replies->add($reply);
            $reply->setParent($this);
        }

        return $this;
    }

    public function removeReply(ForumMessage $reply): static
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getParent() === $this) {
                $reply->setParent(null);
            }
        }

        return $this;
    }

    public function isReply(): bool
    {
        return $this->parent !== null;
    }

    public function hasReplies(): bool
    {
        return !$this->replies->isEmpty();
    }

    public function getReplyCount(): int
    {
        return $this->replies->count();
    }

    public function __toString(): string
    {
        return substr($this->content, 0, 50) . '...';
    }
}
