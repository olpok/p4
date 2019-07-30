<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    //* @Assert\GreaterThanOrEqual("today")

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $dateBirth;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $dateEntry;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $lowPriceAdmission;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $fullDay;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderTicket", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order_ticket;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admission", inversedBy="tickets")
     */
    private $admission;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDateBirth(): ?\DateTimeInterface
    {
        return $this->dateBirth;
    }

    public function setDateBirth(\DateTimeInterface $dateBirth): self
    {
        $this->dateBirth = $dateBirth;

        return $this;
    }

    public function getDateEntry(): ?\DateTimeInterface
    {
        return $this->dateEntry;
    }

    public function setDateEntry(\DateTimeInterface $dateEntry): self
    {
        $this->dateEntry = $dateEntry;

        return $this;
    }


    public function getLowPriceAdmission(): ?bool
    {
        return $this->lowPriceAdmission;
    }

    public function setLowPriceAdmission(bool $lowPriceAdmission): self
    {
        $this->lowPriceAdmission = $lowPriceAdmission;

        return $this;
    }

    public function getFullDay(): ?bool
    {
        return $this->fullDay;
    }

    public function setFullDay(bool $fullDay): self
    {
        $this->fullDay = $fullDay;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getOrderTicket(): ?orderTicket
    {
        return $this->order_ticket;
    }

    public function setOrderTicket(?orderTicket $order_ticket): self
    {
        $this->order_ticket = $order_ticket;

        return $this;
    }

    public function getAdmission(): ?Admission
    {
        return $this->admission;
    }

    public function setAdmission(?Admission $admission): self
    {
        $this->admission = $admission;

        return $this;
    }
}
