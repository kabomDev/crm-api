<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * on utilise soit les annotations soit yaml ce qui est le cas ici
 * ApiResource(
 *  collectionOperations={"POST"},
 *  itemOperations={"GET", "DELETE"}
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"customer:read", "invoice:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:read", "invoice:read"})
     * @Assert\NotBlank(message="Le fullName ne doit pas etre vide")
     * @Assert\Length(min=5, minMessage="Le fullName doit contenir minimum 5 caractères")
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer:read", "invoice:read"})
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:read", "invoice:read"})
     * @Assert\NotBlank(message="L'email ne doit pas etre vide")
     * @Assert\Email(message="l'adresse email doit etre au bon format")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Invoice::class, mappedBy="customer", orphanRemoval=true)
     * @Groups({"customer:read"})
     */
    private $invoices;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"customer:read", "invoice:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     */
    private $user;

    /**
     * @ORM\PrePersist
     *
     * @return void
     */
    public function prePersist()
    {
        if (!$this->createdAt) {
            $this->createdAt = new DateTime();
        }

        if (!$this->updatedAt) {
            $this->updatedAt = new DateTime();
        }
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

    /**
     * @Groups({"customer:read", "invoice:read"})
     */
    public function getInvoicesCount()
    {
        return count($this->invoices);
    }

    /**
     * @Groups({"customer:read", "invoice:read"})
     */
    public function getTotalAmount()
    {
        // $amount = 0;

        // foreach ($this->invoices as $invoice) {
        //     $amount += $invoice->getAmount();
        // }

        // return $amount;

        return array_reduce($this->invoices->toArray(), function (int $total, Invoice $invoice) {
            return $total += $invoice->getAmount();
        }, 0);
    }

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setCustomer($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getCustomer() === $this) {
                $invoice->setCustomer(null);
            }
        }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
