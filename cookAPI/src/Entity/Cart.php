<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['cart'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart'])]
    private User $user;

    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'cart', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['cart'])]
    private Collection $items;

    #[ORM\OneToOne(mappedBy: 'cart', cascade: ['persist', 'remove'])]
    private ?OrderEntity $orderEntity = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(CartItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setCart($this);
        }

        return $this;
    }

    public function removeItem(CartItem $item): self
    {
        if ($this->items->removeElement($item)) {
            if ($item->getCart() === $this) {
                $item->setCart(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOrderEntity(): ?OrderEntity
    {
        return $this->orderEntity;
    }

    public function setOrderEntity(?OrderEntity $orderEntity): static
    {
        // unset the owning side of the relation if necessary
        if ($orderEntity === null && $this->orderEntity !== null) {
            $this->orderEntity->setCart(null);
        }

        // set the owning side of the relation if necessary
        if ($orderEntity !== null && $orderEntity->getCart() !== $this) {
            $orderEntity->setCart($this);
        }

        $this->orderEntity = $orderEntity;

        return $this;
    }
}
