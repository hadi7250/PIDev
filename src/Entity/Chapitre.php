<?php

namespace App\Entity;

use App\Repository\ChapitreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 

#[ORM\Entity(repositoryClass: ChapitreRepository::class)]
#[ORM\Table(name: 'chapitre')]
class Chapitre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_chapitre')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le titre du chapitre est obligatoire.")]
    #[Assert\Length(
        min: 3, 
        max: 100, 
        minMessage: "Le titre est trop court (min {{ limit }} caractères).", 
        maxMessage: "Le titre est trop long."
    )]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le contenu ne peut pas être vide.")]
    #[Assert\Length(max: 255, maxMessage: "Le contenu est limité à 255 caractères.")]
    private ?string $contenu = null;

    #[ORM\ManyToOne(inversedBy: 'chapitres')]
    #[ORM\JoinColumn(name: 'id_cours', referencedColumnName: 'id_cours', nullable: false)]
    private ?Cours $cours = null;

    // ⬇️ THE NEW AI SUMMARY PROPERTY
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $aiSummary = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;
        return $this;
    }

    // ⬇️ THE NEW GETTER AND SETTER
    public function getAiSummary(): ?string
    {
        return $this->aiSummary;
    }

    public function setAiSummary(?string $aiSummary): static
    {
        $this->aiSummary = $aiSummary;
        return $this;
    }
}