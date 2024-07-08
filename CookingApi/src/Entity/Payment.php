<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['payment', 'order'])]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['payment'])]
    private ?Order $userOrder = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd-m-Y H:i:s'])]
    #[Groups(['payment'])]
    private ?\DateTimeInterface $paymentDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups(['payment'])]
    private ?string $amount = null;

    #[ORM\Column(length: 255)]
    #[Groups(['payment'])]
    private ?string $paymentMethod = null;

    #[ORM\Column(length: 255)]
    #[Groups(['payment'])]
    private ?string $paymentStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserOrder(): ?Order
    {
        return $this->userOrder;
    }

    public function setUserOrder(?Order $userOrder): static
    {
        $this->userOrder = $userOrder;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTimeInterface $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }
}

