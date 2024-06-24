<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Category $category_id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $difficulty = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $timeprepare = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $timecook = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tips = null;

    #[ORM\Column(length: 50)]
    private ?string $nutri_score = null;

    #[ORM\Column(nullable: true)]
    private ?int $net_weight = null;

    #[ORM\Column(nullable: true)]
    private ?int $energy_per_100g = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $allergens = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryId(): ?Category
    {
        return $this->category_id;
    }

    public function setCategoryId(?Category $category_id): static
    {
        $this->category_id = $category_id;

        return $this;
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

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

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

    public function getTimeprepare(): ?\DateTimeImmutable
    {
        return $this->timeprepare;
    }

    public function setTimeprepare(\DateTimeImmutable $timeprepare): static
    {
        $this->timeprepare = $timeprepare;

        return $this;
    }

    public function getTimecook(): ?\DateTimeImmutable
    {
        return $this->timecook;
    }

    public function setTimecook(\DateTimeImmutable $timecook): static
    {
        $this->timecook = $timecook;

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

    public function getNutriScore(): ?string
    {
        return $this->nutri_score;
    }

    public function setNutriScore(string $nutri_score): static
    {
        $this->nutri_score = $nutri_score;

        return $this;
    }

    public function getNetWeight(): ?int
    {
        return $this->net_weight;
    }

    public function setNetWeight(?int $net_weight): static
    {
        $this->net_weight = $net_weight;

        return $this;
    }

    public function getEnergyPer100g(): ?int
    {
        return $this->energy_per_100g;
    }

    public function setEnergyPer100g(?int $energy_per_100g): static
    {
        $this->energy_per_100g = $energy_per_100g;

        return $this;
    }

    public function getAllergens(): ?string
    {
        return $this->allergens;
    }

    public function setAllergens(string $allergens): static
    {
        $this->allergens = $allergens;

        return $this;
    }
}
