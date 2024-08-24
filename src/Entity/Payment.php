<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbTransaction = null;

    #[ORM\Column(length: 150)]
    private ?string $paymentMethod= null;

    #[ORM\Column]
    private ?\DateTimeImmutable $paymentDate = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    private ?StatusPayment $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getnbTransaction (): ?int
    {
        return $this->nbTransaction ;
    }

    public function setNumeroTransaction(int $nbTransaction ): static
    {
        $this->nbTransaction  = $nbTransaction ;

        return $this;
    }

    public function getpaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setpaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getpaymentDate(): ?\DateTimeImmutable
    {
        return $this->paymentDate;
    }

    public function setpaymentDate(\DateTimeImmutable $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getStatus(): ?StatusPayment
    {
        return $this->status;
    }

    public function setStatus(?StatusPayment $status): static
    {
        $this->status = $status;

        return $this;
    }
}
