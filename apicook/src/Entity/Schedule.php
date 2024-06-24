<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $chef_id = null;

    #[ORM\Column(length: 255)]
    private ?string $day = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $open_time = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $close_time = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChefId(): ?int
    {
        return $this->chef_id;
    }

    public function setChefId(int $chef_id): static
    {
        $this->chef_id = $chef_id;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getOpenTime(): ?\DateTimeImmutable
    {
        return $this->open_time;
    }

    public function setOpenTime(\DateTimeImmutable $open_time): static
    {
        $this->open_time = $open_time;

        return $this;
    }

    public function getCloseTime(): ?\DateTimeImmutable
    {
        return $this->close_time;
    }

    public function setCloseTime(\DateTimeImmutable $close_time): static
    {
        $this->close_time = $close_time;

        return $this;
    }
}
