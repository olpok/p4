<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderTicketRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="order_ticket", cascade={"persist","remove"})
     * @Assert\Valid
     */
    private $tickets;


    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->code = date('Ymdhis');
    }
   
    /**
     * @ORM\PrePersist
     */
    public function setDateOrderPrePersist()
    {
        if($this->getDateOrder() === null) {
        $this->setDateOrder($this->createDateOrder());
        }
    }

    public function createDateOrder() {
        return (new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->price = $code;

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