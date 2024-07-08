<?php

namespace App\Entity;

use App\Repository\AccommodationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccommodationRepository::class)]
class Accommodation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 200)]
    private ?string $address = null;

    #[ORM\Column]
    private ?float $priceNight = null;

    #[ORM\Column]
    private ?int $NbGuests = null;

    #[ORM\Column]
    private ?int $NbRooms = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateDate = null;

    /**
     * @var Collection<int, Picture>
     */
    #[ORM\OneToMany(targetEntity: Picture::class, mappedBy: 'accommodation')]
    private Collection $pictures;

    #[ORM\ManyToOne(inversedBy: 'accommodations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $host = null;

    #[ORM\ManyToOne(inversedBy: 'accommodations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?city $city = null;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'accommodation')]
    private Collection $reviews;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'accommodation')]
    private Collection $reservations;



    /**
     * @var Collection<int, Review>
     */


    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->reservations = new ArrayCollection();


    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPriceNight(): ?float
    {
        return $this->priceNight;
    }

    public function setPriceNight(float $priceNight): static
    {
        $this->priceNight = $priceNight;

        return $this;
    }

    public function getNbGuests(): ?int
    {
        return $this->NbGuests;
    }

    public function setNbGuests(int $NbGuests): static
    {
        $this->NbGuests = $NbGuests;

        return $this;
    }

    public function getNbRooms(): ?int
    {
        return $this->NbRooms;
    }

    public function setNbRooms(int $NbRooms): static
    {
        $this->NbRooms = $NbRooms;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeImmutable
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeImmutable $createDate): static
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeImmutable
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeImmutable $updateDate): static
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setAccommodation($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getAccommodation() === $this) {
                $picture->setAccommodation(null);
            }
        }

        return $this;
    }

    public function getHost(): ?user
    {
        return $this->host;
    }

    public function setHost(?user $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function getCity(): ?city
    {
        return $this->city;
    }

    public function setCity(?city $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setAccommodation($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getAccommodation() === $this) {
                $review->setAccommodation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setAccommodation($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getAccommodation() === $this) {
                $reservation->setAccommodation(null);
            }
        }

        return $this;
    }


}
