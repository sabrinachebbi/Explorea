<?php

namespace App\Entity;

use App\Repository\reviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $note = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateReView = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $customer = null;


    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Activity $Activity = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Accommodation $Accommodation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDateReView(): ?\DateTimeImmutable
    {
        return $this->dateReView;
    }

    public function setDateReView(\DateTimeImmutable $dateReView): static
    {
        $this->dateReView = $dateReView;

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

    public function getActivity(): ?Activity
    {
        return $this->Activity;
    }

    public function setActivity(?activity $Activity): static
    {
        $this->Activity = $Activity;

        return $this;
    }

    public function getAccommodation(): ?Accommodation
    {
        return $this->Accommodation;
    }

    public function setAccommodation(?accommodation $Accommodation): static
    {
        $this->Accommodation = $Accommodation;

        return $this;
    }
}
