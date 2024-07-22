<?php

namespace App\Entity;

use App\Enum\statusResv;
use App\Repository\ReservationStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationStatusRepository::class)]
class ReservationStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: statusResv::class)]
    private ?statusResv $status = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'status')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?statusResv
    {
        return $this->status;
    }

    public function setStatus(statusResv $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $Reservation): static
    {
        if (!$this->reservations->contains($Reservation)) {
            $this->reservations->add($Reservation);
            $Reservation->setStatus($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $Reservation): static
    {
        if ($this->reservations->removeElement($Reservation)) {
            // set the owning side to null (unless already changed)
            if ($Reservation->getStatus() === $this) {
                $Reservation->setStatus(null);
            }
        }

        return $this;
    }
}
