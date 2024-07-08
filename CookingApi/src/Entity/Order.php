<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order', 'user'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order'])]
    private ?User $user = null;

    #[ORM\Column]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd-m-Y H:i:s'])]
    #[Groups(['order'])]
    private ?\DateTimeImmutable $orderDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd-m-Y H:i:s'])]
    #[Groups(['order'])]
    private ?\DateTimeInterface $pickupTime = null;

    #[ORM\Column(length: 255)]
    #[Groups(['order'])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups(['order'])]
    private ?string $totalPrice = null;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'userOrder', orphanRemoval: true)]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderDate(): ?\DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeImmutable $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getPickupTime(): ?\DateTimeInterface
    {
        return $this->pickupTime;
    }

    public function setPickupTime(\DateTimeInterface $pickupTime): static
    {
        $this->pickupTime = $pickupTime;

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

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setUserOrder($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getUserOrder() === $this) {
                $payment->setUserOrder(null);
            }
        }

        return $this;
    }
}
