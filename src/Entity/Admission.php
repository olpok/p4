<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdmissionRepository")
 */
class Admission
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    // @Assert\PositiveOrZero

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $amountLabel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $constant_key;

    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    private $amount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="admission")
     */
    private $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

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

    public function getConstantKey(): ?string
    {
        return $this->constant_key;
    }

    public function setConstantKey(?string $constant_key): self
    {
        $this->constant_key = $constant_key;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountLabel() : ?string
    {
       return $this->amountLabel;
    }
    
     public function setAmountLabel(?string $amountLabel): self
    {
        $this->amountLabel = $amountLabel;

        return $this;
    }
   
    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setAdmission($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            // set the owning side to null (unless already changed)
            if ($ticket->getAdmission() === $this) {
                $ticket->setAdmission(null);
            }
        }

        return $this;
    }
}
