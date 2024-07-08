<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['menu', 'category', 'user'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['menu', 'category', 'user'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['menu', 'category', 'user'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['menu', 'category', 'user'])]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(['menu', 'category', 'user'])]
    private ?bool $availability = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    #[Groups(['menu'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    #[Groups(['menu'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $chef = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): static
    {
        $this->availability = $availability;

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

    public function getChef(): ?User
    {
        return $this->chef;
    }

    public function setChef(?User $chef): static
    {
        $this->chef = $chef;

        return $this;
    }
}
