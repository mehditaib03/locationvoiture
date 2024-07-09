<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reservation_group','car_group'])]
    private ?int $id = null;
    
    #[Groups(['reservation_group','car_group'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;
    
    #[Groups(['reservation_group','car_group'])]
    #[ORM\Column(length: 255)]
    private ?string $model = null;
    
    #[Groups(['reservation_group','car_group'])]
    #[ORM\Column(length: 255)]
    private ?string $year = null;


    #[ORM\Column(type: "datetime")]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\Column(type: "datetime")]
    private ?DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, reservation>
     */
    #[ORM\OneToMany(targetEntity: reservation::class, mappedBy: 'car')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

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

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['reservation_group'])]
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    #[Groups(['reservation_group'])]
    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    #[Groups(['reservation_group'])]
    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setCar($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getCar() === $this) {
                $reservation->setCar(null);
            }
        }

        return $this;
    }
}
