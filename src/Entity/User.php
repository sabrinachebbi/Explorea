<?php

namespace App\Entity;

use App\Enum\typeUser;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
    #[ORM\Column]
    private ?\DateTimeImmutable $dateInscription = null;

    #[ORM\Column(length: 100)]
    private ?string $overallReview = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserProfile $userProfile = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'host')]
    private Collection $activities;

    /**
     * @var Collection<int, Accommodation>
     */
    #[ORM\OneToMany(targetEntity: Accommodation::class, mappedBy: 'host')]
    private Collection $accommodations;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'customer')]
    private Collection $reviews;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'customer')]
    private Collection $reservations;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->accommodations = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function getDateInscription(): ?\DateTimeImmutable
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeImmutable $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getoverallReview(): ?string
    {
        return $this->overallReview;
    }

    public function setoverallReview(string $overallReview): static
    {
        $this->overallReview = $overallReview;

        return $this;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(UserProfile $UserProfile): static
    {
        if ($UserProfile->getUser() !== $this) {
            $UserProfile->setUser($this);
        }

        $this->userProfile = $UserProfile;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $Activity): static
    {
        if (!$this->activities->contains($Activity)) {
            $this->activities->add($Activity);
            $Activity->setHôte($this);
        }

        return $this;
    }

    public function removeActivity(Activity $Activity): static
    {
        if ($this->activities->removeElement($Activity)) {
            // set the owning side to null (unless already changed)
            if ($Activity->getHôte() === $this) {
                $Activity->setHôte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Accommodation>
     */
    public function getAccommodations(): Collection
    {
        return $this->accommodations;
    }

    public function addAccommodation(Accommodation $Accommodation): static
    {
        if (!$this->accommodations->contains($Accommodation)) {
            $this->accommodations->add($Accommodation);
            $Accommodation->setHost($this);
        }

        return $this;
    }

    public function removeAccommodation(Accommodation $Accommodation): static
    {
        if ($this->accommodations->removeElement($Accommodation)) {
            // set the owning side to null (unless already changed)
            if ($Accommodation->getHost() === $this) {
                $Accommodation->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $Review): static
    {
        if (!$this->reviews->contains($Review)) {
            $this->reviews->add($Review);
            $Review->setCustomer($this);
        }

        return $this;
    }

    public function removeReview(Review $Review): static
    {
        if ($this->reviews->removeElement($Review)) {
            // set the owning side to null (unless already changed)
            if ($Review->getCustomer() === $this) {
                $Review->setCustomer(null);
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

    public function addReservation(Reservation $Reservation): static
    {
        if (!$this->reservations->contains($Reservation)) {
            $this->reservations->add($Reservation);
            $Reservation->setCustomer($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $Reservation): static
    {
        if ($this->reservations->removeElement($Reservation)) {

            if ($Reservation->getCustomer() === $this) {
                $Reservation->setCustomer(null);
            }
        }

        return $this;
    }

}
