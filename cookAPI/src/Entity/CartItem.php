<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['cart', 'order', 'order_items'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Recipe::class)]
    #[Groups(['cart', 'order', 'order_items'])]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    #[Groups(['cart', 'order', 'order_items'])]
    private ?Cart $cart = null;

    #[ORM\Column(type: 'integer')]
    #[Groups(['cart', 'order', 'order_items'])]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    private ?OrderEntity $orderEntity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;
        return $this;
    }

    public function getOrderEntity(): ?OrderEntity
    {
        return $this->orderEntity;
    }

    public function setOrderEntity(?OrderEntity $orderEntity): self
    {
        $this->orderEntity = $orderEntity;
        return $this;
    }
}
