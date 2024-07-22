<?php

namespace App\Entity;

use App\Repository\countryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, City>
     */
    #[ORM\OneToMany(targetEntity: City::class, mappedBy: 'Country')]
    private Collection $cities;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, City>
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $City): static
    {
        if (!$this->cities->contains($City)) {
            $this->cities->add($City);
            $City->setCountry($this);
        }

        return $this;
    }

    public function removeCity(City $City): static
    {
        if ($this->cities->removeElement($City)) {
            // set the owning side to null (unless already changed)
            if ($City->getCountry() === $this) {
                $City->setCountry(null);
            }
        }

        return $this;
    }
}
