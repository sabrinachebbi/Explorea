<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[vich\Uploadable()]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $name = null;
    #[Vich\UploadableField(mapping: 'accommodations', fileNameProperty: 'name')]
    private ?File $accommodationImageFile = null ;

    #[Vich\UploadableField(mapping: 'activities', fileNameProperty: 'name')]
    private ?File $activityImageFile = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToOne(targetEntity: Activity::class, inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?accommodation $accommodation = null;


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }


    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }
    public function setAccommodationImageFile(?File $imageFile): self
    {
        $this->accommodationImageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updateAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getAccommodationImageFile(): ?File
    {
        return $this->accommodationImageFile;
    }

    public function setActivityImageFile(?File $imageFile): self
{
    $this->activityImageFile = $imageFile;
    if (null !== $imageFile) {
        $this->updateAt = new \DateTimeImmutable();
    }
    return $this;
}

    public function getActivityImageFile(): ?File
    {
        return $this->activityImageFile;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

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
}
