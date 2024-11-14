<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
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
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateReView = null;

    #[ORM\OneToOne(mappedBy: 'reviews', cascade: ['persist', 'remove'])]
    private ?Reservation $reservation = null;


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

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        // unset the owning side of the relation if necessary
        if ($reservation === null && $this->reservation !== null) {
            $this->reservation->setReviews(null);
        }

        // set the owning side of the relation if necessary
        if ($reservation !== null && $reservation->getReviews() !== $this) {
            $reservation->setReviews($this);
        }

        $this->reservation = $reservation;

        return $this;
    }


}
