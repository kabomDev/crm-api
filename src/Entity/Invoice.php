<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InvoiceRepository;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Invoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"customer:read", "invoice:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"customer:read", "invoice:read"})
     * @Assert\NotBlank(message = "le montant doit etre obligatoire")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"customer:read", "invoice:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"customer:read", "invoice:read"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"customer:read", "invoice:read"})
     */
    private $chrono;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:read", "invoice:read"})
     * @Assert\NotBlank(message = "le titre de la facture doit etre obligatoire")
     * @Assert\Length(min=5, minMessage= "le titre doit contenir au minimum 5 caractères")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="invoices")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"invoice:read"})
     * @Assert\NotBlank(message="vous devez assigner un client à cette facture")
     */
    private $customer;

    /**
     * @ORM\PrePersist
     *
     * @return void
     */
    public function prePersist()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function preUpdate()
    {
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getChrono(): ?int
    {
        return $this->chrono;
    }

    public function setChrono(int $chrono): self
    {
        $this->chrono = $chrono;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
