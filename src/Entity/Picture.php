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

    #[ORM\ManyToOne(targetEntity: Accommodation::class, inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Accommodation $accommodationPictures = null;

    #[ORM\ManyToOne(targetEntity: Activity::class, inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Activity $activityPictures = null;

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

    public function getAccommodationPictures(): ?Accommodation
    {
        return $this->accommodationPictures;
    }

    public function setAccommodationPictures(?Accommodation $accommodationPictures): static
    {
        $this->accommodationPictures = $accommodationPictures;

        return $this;
    }

    public function getActivityPictures(): ?Activity
    {
        return $this->activityPictures;
    }

    public function setActivityPictures(?Activity $activityPictures): static
    {
        $this->activityPictures = $activityPictures;

        return $this;
    }
}
