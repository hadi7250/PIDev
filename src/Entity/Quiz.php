<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // Import Validation

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The title is required.")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Title must be at least 3 chars.")]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: "Please provide a description.")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive(message: "Time limit must be a positive number (e.g. 10 minutes).")]
    private ?int $timeLimit = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "Total score cannot be negative.")]
    private ?int $totalScore = null;

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
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getTimeLimit(): ?int { return $this->timeLimit; }
    public function setTimeLimit(int $timeLimit): static { $this->timeLimit = $timeLimit; return $this; }

    public function getTotalScore(): ?int { return $this->totalScore; }
    public function setTotalScore(int $totalScore): static { $this->totalScore = $totalScore; return $this; }

    public function getQuestions(): Collection { return $this->questions; }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuiz($this);
        }
        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }
        return $this;
    }

    
}