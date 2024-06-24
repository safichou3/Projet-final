<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?order $order_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?recipe $recipe_id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?bool $is_part_of_formula = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?order
    {
        return $this->order_id;
    }

    public function setOrderId(?order $order_id): static
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getRecipeId(): ?recipe
    {
        return $this->recipe_id;
    }

    public function setRecipeId(?recipe $recipe_id): static
    {
        $this->recipe_id = $recipe_id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function isPartOfFormula(): ?bool
    {
        return $this->is_part_of_formula;
    }

    public function setPartOfFormula(bool $is_part_of_formula): static
    {
        $this->is_part_of_formula = $is_part_of_formula;

        return $this;
    }
}
