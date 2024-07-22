<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $imageURL = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Accommodation $Accommodation = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $Activity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageURL(): ?string
    {
        return $this->imageURL;
    }

    public function setImageURL(string $imageURL): static
    {
        $this->imageURL = $imageURL;

        return $this;
    }

    public function getAccommodation(): ?Accommodation
    {
        return $this->Accommodation;
    }

    public function setAccommodation(?Accommodation $Accommodation): static
    {
        $this->Accommodation = $Accommodation;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->Activity;
    }

    public function setActivity(?Activity $Activity): static
    {
        $this->Activity = $Activity;

        return $this;
    }
}
