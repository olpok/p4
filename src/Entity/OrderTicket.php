<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderTicketRepository")
 */
class OrderTicket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateOrder;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $paymentType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="order_ticket", cascade={"persist","remove"})
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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->dateOrder;
    }

    public function setDateOrder(\DateTimeInterface $dateOrder): self
    {
        $this->dateOrder = $dateOrder;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): self
    {
        $this->paymentType = $paymentType;

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
        if (!$this->tickets->contains($ticket))
         {
            $this->tickets[] = $ticket;
            $ticket->setOrderTicket($this);

            
            // update price
            
            if ($ticket->getFullDay() == 0){
              $ticketPrice = $ticket->getAdmission()->getAmount()/2;  
            } else {
              $ticketPrice = $ticket->getAdmission()->getAmount();
            };
            $currentPrice = $this->price + $ticketPrice;
            $this->setPrice($currentPrice);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);

            // update price => remove price
            $currentPrice = $this->getPrice();
            $currentPrice = $currentPrice - $ticket->getAdmission()->getAmount();
            $this->setPrice($currentPrice);

            // set the owning side to null (unless already changed)
            if ($ticket->getOrderTicket() === $this) {
                $ticket->setOrderTicket(null);
            }
        }

        return $this;
    }
}
