<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $mistakes = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'quizzs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $answeredBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMistakes(): ?int
    {
        return $this->mistakes;
    }

    public function setMistakes(int $mistakes): static
    {
        $this->mistakes = $mistakes;

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

    public function getAnsweredBy(): ?User
    {
        return $this->answeredBy;
    }

    public function setAnsweredBy(?User $answeredBy): static
    {
        $this->answeredBy = $answeredBy;

        return $this;
    }
}
