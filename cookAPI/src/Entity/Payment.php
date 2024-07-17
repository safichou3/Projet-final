<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pay', 'order_payment'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['pay', 'order_payment'])]
    private ?float $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['pay', 'order_payment'])]
    private ?string $method = null;

    #[ORM\Column(length: 255)]
    #[Groups(['pay', 'order_payment'])]
    private ?string $status = null;

    #[ORM\Column]
    #[Groups(['pay', 'order_payment'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToOne(targetEntity: OrderEntity::class, inversedBy: 'payment')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderEntity $order = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOrder(): ?OrderEntity
    {
        return $this->order;
    }

    public function setOrderEn(?OrderEntity $order): static
    {
        $this->order = $order;

        return $this;
    }
}
