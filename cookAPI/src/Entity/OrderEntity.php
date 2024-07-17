<?php

namespace App\Entity;

use App\Repository\OrderEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderEntityRepository::class)]
#[ORM\HasLifecycleCallbacks]
class OrderEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order', 'order_items', 'order_payment', 'cart'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order', 'cart'])]
    private ?User $user = null;

    #[ORM\Column(unique: true)]
    #[Groups(['order', 'cart'])]
    private ?string $order_number = null;

    #[ORM\Column]
    #[Groups(['order', 'cart'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255)]
    #[Groups(['order', 'cart'])]
    private ?string $status = null;

    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'orderEntity', cascade: ['persist', 'remove'])]
    #[Groups(['order_items', 'cart'])]
    private Collection $cartItems;

    #[ORM\OneToOne(targetEntity: Payment::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    #[Groups(['order_payment', 'cart'])]
    private ?Payment $payment = null;

    #[ORM\OneToOne(inversedBy: 'orderEntity', targetEntity: Cart::class)]
    #[ORM\JoinColumn(name: "cart_id", referencedColumnName: "id")]
    private ?Cart $cart = null;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setInitialValues(): void
    {
        if ($this->created_at === null) {
            $this->created_at = new \DateTimeImmutable();
        }
        if ($this->order_number === null) {
            $this->order_number = $this->generateOrderNumber();
        }
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?string
    {
        return $this->order_number;
    }

    public function setOrderNumber(?string $order_number): static
    {
        $this->order_number = $order_number;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems[] = $cartItem;
            $cartItem->setOrderEntity($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->removeElement($cartItem)) {
            if ($cartItem->getOrderEntity() === $this) {
                $cartItem->setOrderEntity(null);
            }
        }

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }
}
