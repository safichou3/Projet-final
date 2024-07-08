<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe', 'recipeIngredient'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 200,
        minMessage: "Le titre doit faire au moins trois caractères",
        maxMessage: "Le titre ne peut pas faire plus de 200 caractères"
    )]
    #[Groups(['recipe', 'recipeIngredient'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        min: 3,
        max: 2000,
        minMessage: "La description doit faire au moins 3 caractères",
        maxMessage: "La description ne peut pas faire plus de 2000 caractères"
    )]
    #[Assert\NotBlank(message: "La description est obligatoire")]
    #[Groups(['recipe'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Choice(['facile', 'moyen', 'difficile'])]
    #[Assert\NotBlank(message: "Veuillez sélectionner une difficulté")]
    #[Groups(['recipe'])]
    private ?string $difficulty = null;

    #[ORM\Column]
    #[Groups(['recipe'])]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd-m-Y à H:i:s'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'H:i:s'])]
    private ?\DateTimeInterface $timePrepare = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'H:i:s'])]
    private ?\DateTimeInterface $timeCook = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max: 4000,
        maxMessage: "Le conseil ne peut pas faire plus de 4000 caractères"
    )]
    #[Groups(['recipe'])]
    private ?string $tips = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['recipe'])]
    private ?string $nutriScore = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe'])]
    private ?int $netWeight = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe'])]
    private ?int $energyPer100g = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['recipe'])]
    private ?string $allergens = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[Groups(['recipe'])]
    private ?Category $category = null;

    /**
     * @var Collection<int, RecipeIngredient>
     */
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe', orphanRemoval: true)]
    private Collection $recipeIngredients;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
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

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): static
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

    public function getTimePrepare(): ?\DateTimeInterface
    {
        return $this->timePrepare;
    }

    public function setTimePrepare(?\DateTimeInterface $timePrepare): static
    {
        $this->timePrepare = $timePrepare;

        return $this;
    }

    public function getTimeCook(): ?\DateTimeInterface
    {
        return $this->timeCook;
    }

    public function setTimeCook(?\DateTimeInterface $timeCook): static
    {
        $this->timeCook = $timeCook;

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
        return $this->nutriScore;
    }

    public function setNutriScore(?string $nutriScore): static
    {
        $this->nutriScore = $nutriScore;

        return $this;
    }

    public function getNetWeight(): ?int
    {
        return $this->netWeight;
    }

    public function setNetWeight(?int $netWeight): static
    {
        $this->netWeight = $netWeight;

        return $this;
    }

    public function getEnergyPer100g(): ?int
    {
        return $this->energyPer100g;
    }

    public function setEnergyPer100g(?int $energyPer100g): static
    {
        $this->energyPer100g = $energyPer100g;

        return $this;
    }

    public function getAllergens(): ?string
    {
        return $this->allergens;
    }

    public function setAllergens(?string $allergens): static
    {
        $this->allergens = $allergens;

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

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): static
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }
}
