<?php

namespace App\Entity;

use App\Enum\GenderEnum;
use App\Repository\UserProfileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateBirth = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 100)]
    private ?string $country = null;

    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[ORM\Column(length: 100)]
    private ?string $address = null;

    #[ORM\Column(enumType: GenderEnum::class)]
    private ?GenderEnum $Gender = null;

    #[ORM\OneToOne(inversedBy: 'userProfile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 150)]
    private ?string $FirstName = null;

    #[ORM\Column(length: 200)]
    private ?string $lastName = null;

    public function getGender():?GenderEnum
    {
        return $this->Gender;
    }
    public function setGender(GenderEnum $Gender):?self
    {
        $this->Gender = $Gender;
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getdateBirth(): ?\DateTimeInterface
    {
        return $this->dateBirth;
    }

    public function setdateBirth(\DateTimeInterface $dateBirth): static
    {
        $this->dateBirth= $dateBirth;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $City): static
    {
        $this->city = $City;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $Address): static
    {
        $this->address = $Address;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): static
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }


}
