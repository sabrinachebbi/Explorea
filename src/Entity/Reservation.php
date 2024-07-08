<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $departureDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $returnDate = null;

    #[ORM\Column]
    private ?int $nbAdults = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbChildren = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateModification = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $customer = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?accommodation $accommodation = null;

    /**
     * @var Collection<int, activity>
     */
    #[ORM\ManyToMany(targetEntity: activity::class, inversedBy: 'reservations')]
    private Collection $activities;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?reservationStatus $status = null;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureDate(): ?\DateTimeImmutable
    {
        return $this->departureDate;
    }

    public function setDepartureDate(\DateTimeImmutable $departureDate): static
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeImmutable
    {
        return $this->returnDate;
    }

    public function setReturnDate(\DateTimeImmutable $returnDate): static
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function getNbAdults(): ?int
    {
        return $this->nbAdults;
    }

    public function setNbAdults(int $nbAdults): static
    {
        $this->nbAdults = $nbAdults;

        return $this;
    }

    public function getChildreb(): ?int
    {
        return $this->nbChildren;
    }

    public function setNbChildren(?int $nbChildren): static
    {
        $this->nbChildren = $nbChildren;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeImmutable
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeImmutable $dateModification): static
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getCustomer(): ?user
    {
        return $this->customer;
    }

    public function setCustomer(?user $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getAccommodation(): ?accommodation
    {
        return $this->accommodation;
    }

    public function setAccommodation(?accommodation $accommodation): static
    {
        $this->accommodation = $accommodation;

        return $this;
    }

    /**
     * @return Collection<int, activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
        }

        return $this;
    }

    public function removeActivity(activity $activity): static
    {
        $this->activities->removeElement($activity);

        return $this;
    }

    public function getStatus(): ?reservationStatus
    {
        return $this->status;
    }

    public function setStatus(?reservationStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}