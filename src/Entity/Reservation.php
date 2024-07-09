<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reservation_group'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[Groups(['reservation_group'])]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[Groups(['reservation_group'])]
    private ?Car $car = null;


    #[ORM\Column(type: "datetime")]
    #[Groups(['reservation_group'])]
    private ?DateTimeInterface $startTime = null;

    #[ORM\Column(type: "datetime")]
    #[Groups(['reservation_group'])]
    private ?DateTimeInterface $endTime = null;

    #[Groups(['reservation_group'])]
    public function getId(): ?int
    {
        return $this->id;
    }
    #[ORM\Column(type: "datetime")]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\Column(type: "datetime")]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTime("now");
        $this->updatedAt = new \DateTime("now");
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime("now");
    }

    #[Groups(['reservation_group'])]
    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    #[Groups(['reservation_group'])]
    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    #[Groups(['reservation_group'])]
    public function getStartTime(): ?DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;
        return $this;
    }

    #[Groups(['reservation_group'])]
    public function getEndTime(): ?DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }
}
