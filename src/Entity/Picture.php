<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;


#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[Vich\Uploadable]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping: 'accommodations', fileNameProperty: 'name')]
    private ?File $accommodationImageFile = null;

    #[Vich\UploadableField(mapping: 'activities', fileNameProperty: 'name')]
    private ?File $activityImageFile = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?Accommodation $accommodation = null;

    #[ORM\OneToOne(mappedBy: 'picture', cascade: ['persist', 'remove'])]
    private ?Activity $activity = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;
        return $this;
    }


    public function getAccommodation(): ?Accommodation
    {
        return $this->accommodation;
    }

    public function setAccommodation(?Accommodation $accommodation): self
    {
        $this->accommodation = $accommodation;
        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        // Manage the OneToOne relationship
        if ($activity === null && $this->activity !== null) {
            $this->activity->setPicture(null);
        }

        if ($activity !== null && $activity->getPicture() !== $this) {
            $activity->setPicture($this);
        }

        $this->activity = $activity;
        return $this;
    }
    public function getAccommodationImageFile(): ?File
    {
        return $this->accommodationImageFile;
    }

    public function setAccommodationImageFile(?File $accommodationImageFile): void
    {
        $this->accommodationImageFile = $accommodationImageFile;

        if ($accommodationImageFile) {
            $this->name = $accommodationImageFile->getFilename();
            $this->updateAt = new \DateTimeImmutable();
        }
    }
    public function getActivityImageFile(): ?File
    {
        return $this->activityImageFile;
    }

    public function setActivityImageFile(?File $activityImageFile): void
    {
        $this->activityImageFile = $activityImageFile;

        if ($activityImageFile) {
            $this->updateAt = new \DateTimeImmutable();
        }
    }

}
