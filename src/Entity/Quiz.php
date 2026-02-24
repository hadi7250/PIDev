<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Title is required.")]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Description is required.")]
    private ?string $description = null;

    // ⬇️ FIXED: Mapped to 'time_limit' in database
    #[ORM\Column(name: 'time_limit')]
    #[Assert\Positive(message: "Time limit must be positive.")]
    private ?int $timeLimit = null;

    // ⬇️ FIXED: Mapped to 'total_score' in database
    #[ORM\Column(name: 'total_score')]
    #[Assert\Positive(message: "Total score must be positive.")]
    private ?int $totalScore = null;

    #[ORM\ManyToOne(targetEntity: Cours::class)]
    #[ORM\JoinColumn(name: 'id_cours', referencedColumnName: 'id_cours', nullable: true)] 
    private ?Cours $cours = null;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }
    public function getTimeLimit(): ?int { return $this->timeLimit; }
    public function setTimeLimit(int $timeLimit): static { $this->timeLimit = $timeLimit; return $this; }
    public function getTotalScore(): ?int { return $this->totalScore; }
    public function setTotalScore(int $totalScore): static { $this->totalScore = $totalScore; return $this; }
    public function getCours(): ?Cours { return $this->cours; }
    public function setCours(?Cours $cours): static { $this->cours = $cours; return $this; }
    public function getQuestions(): Collection { return $this->questions; }
}