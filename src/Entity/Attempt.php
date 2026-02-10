<?php

namespace App\Entity;

use App\Repository\AttemptRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttemptRepository::class)]
class Attempt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Quiz::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quiz $quiz = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $score = null;

    #[ORM\Column(nullable: true)]
    private array $answers = [];

    public function __construct()
    {
        $this->startedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getQuiz(): ?Quiz { return $this->quiz; }
    public function setQuiz(?Quiz $quiz): static { $this->quiz = $quiz; return $this; }

    public function getStartedAt(): ?\DateTimeImmutable { return $this->startedAt; }
    public function setStartedAt(\DateTimeImmutable $startedAt): static { $this->startedAt = $startedAt; return $this; }

    public function getCompletedAt(): ?\DateTimeImmutable { return $this->completedAt; }
    public function setCompletedAt(?\DateTimeImmutable $completedAt): static { $this->completedAt = $completedAt; return $this; }

    public function getScore(): ?int { return $this->score; }
    public function setScore(?int $score): static { $this->score = $score; return $this; }

    public function getAnswers(): array { return $this->answers; }
    public function setAnswers(?array $answers): static { $this->answers = $answers; return $this; }
}