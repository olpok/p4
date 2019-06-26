<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $adult;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $child;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $senior;

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

    public function getAdult(): ?int
    {
        return $this->adult;
    }

    public function setAdult(?int $adult): self
    {
        $this->adult = $adult;

        return $this;
    }

    public function getChild(): ?int
    {
        return $this->child;
    }

    public function setChild(?int $child): self
    {
        $this->child = $child;

        return $this;
    }

    public function getSenior(): ?int
    {
        return $this->senior;
    }

    public function setSenior(?int $senior): self
    {
        $this->senior = $senior;

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
