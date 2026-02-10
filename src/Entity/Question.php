<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'question')]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'text', type: 'text')]
    #[Assert\NotBlank]
    private ?string $questionText = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    // ⬇️ THIS MATCHES YOUR NEW 'options' COLUMN
    #[ORM\Column(type: 'json')]
    #[Assert\Count(min: 2, minMessage: "At least 2 options required.")]
    private array $options = [];

    // ⬇️ THIS MATCHES YOUR NEW 'correct_answer' COLUMN
    #[ORM\Column(name: 'correct_answer', length: 255)]
    #[Assert\NotBlank]
    private ?string $correctAnswer = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $points = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'id_quiz', referencedColumnName: 'id', nullable: false)]
    private ?Quiz $quiz = null;

    public function getId(): ?int { return $this->id; }

    public function getQuestionText(): ?string { return $this->questionText; }
    public function setQuestionText(string $questionText): static { $this->questionText = $questionText; return $this; }

    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }

    public function getOptions(): array { return $this->options; }
    public function setOptions(array $options): static { $this->options = $options; return $this; }

    public function getCorrectAnswer(): ?string { return $this->correctAnswer; }
    public function setCorrectAnswer(string $correctAnswer): static { $this->correctAnswer = $correctAnswer; return $this; }

    public function getPoints(): ?int { return $this->points; }
    public function setPoints(int $points): static { $this->points = $points; return $this; }

    public function getQuiz(): ?Quiz { return $this->quiz; }
    public function setQuiz(?Quiz $quiz): static { $this->quiz = $quiz; return $this; }
}