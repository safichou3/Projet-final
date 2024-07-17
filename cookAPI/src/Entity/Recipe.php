<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('recipe')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 200,
        minMessage: "Le titre doit faire au moins 3 caractères",
        maxMessage: "Le titre ne peut pas faire plus de 200 caractères"
    )]
    #[Assert\NotBlank(message: "Le titre est obligatoire")]
    #[Groups('recipe')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min: 3, max: 2000)]
    #[Assert\NotBlank]
    #[Groups('recipe')]
    private ?string $description = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'H:i:s d-m-Y'])]
    #[Groups('recipe')]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'H:i:s d-m-Y'])]
    #[Groups('recipe')]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 4000)]
    #[Groups('recipe')]
    private ?string $tips = null;

    #[ORM\ManyToOne(inversedBy: 'recipes', cascade: ['persist'])]
    #[Groups('recipe')]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Choice(['facile', 'moyen', 'difficile'])]
    #[Assert\NotBlank(message: "Veuillez sélectionner une difficulté")]
    #[Groups('recipe')]
    private ?string $difficulty = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[Groups('recipe')]
    private ?User $user = null;

    // Ajouter une méthode prePersist pour définir la date de création
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        if ($this->created_at === null) {
            $this->created_at = new \DateTimeImmutable();
        }
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getTips(): ?string
    {
        return $this->tips;
    }

    public function setTips(?string $tips): static
    {
        $this->tips = $tips;

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

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
