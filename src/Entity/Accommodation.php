<?php

namespace App\Entity;

use App\Enum\propertyType;
use App\Repository\AccommodationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: AccommodationRepository::class)]
#[Vich\Uploadable]
class Accommodation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\Length(min: 3, max: 50)]
    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 10, max: 500, minMessage: "La Description doit comporter au moins 10 caractères.")]
    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    private ?string $description = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    #[Assert\Length(min: 5, max: 250, minMessage: "L'adresse doit comporter au moins 5 caractères.")]
    private ?string $address = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    private ?float $priceNight = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    #[Assert\Type(type: 'integer', message: "La valeur doit être un nombre entier.")]
    #[Assert\GreaterThan(value: 0, message: "Le nombre doit être supérieur à 0.")]
    private ?int $NbGuests = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    #[Assert\Type(type: 'integer', message: "La valeur doit être un nombre entier.")]
    #[Assert\GreaterThan(value: 0, message: "Le nombre doit être supérieur à 0.")]
    private ?int $NbRooms = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createDate = null;

    #[ORM\Column(type: "datetime_immutable", nullable: false)]
    private ?\DateTimeImmutable $updateDate = null;

    #[ORM\Column(enumType: propertyType::class)]
    #[Assert\NotBlank(message: "Veuillez remplir ce champ.")]
    private ?propertyType $propertyType = null;

    #[ORM\ManyToOne(inversedBy: 'accommodations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $host = null;

    #[ORM\ManyToOne(inversedBy: 'accommodations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\OneToMany(mappedBy: 'accommodation', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'accommodation', targetEntity: Picture::class, cascade: ['persist', 'remove'])]
    private Collection $pictures;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    // Getters et Setters

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

    public function getPropertyType(): ?propertyType
    {
        return $this->propertyType;
    }

    public function setPropertyType(propertyType $propertyType): static
    {
        $this->propertyType = $propertyType;
        return $this;
    }

    public function getHost(): ?User
    {
        return $this->host;
    }

    public function setHost(?User $host): static
    {
        $this->host = $host;
        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;
        return $this;
    }

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
            if ($reservation->getAccommodation() === $this) {
                $reservation->setAccommodation(null);
            }
        }
        return $this;
    }

    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
//            $this->pictures->add($picture);
            $picture->setAccommodation($this);
        }
        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            if ($picture->getAccommodation() === $this) {
                $picture->setAccommodation(null);
            }
        }
        return $this;
    }


}
