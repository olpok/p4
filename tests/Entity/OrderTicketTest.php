<?php

namespace Tests\Entity;

use App\Entity\Ticket;
use App\Entity\OrderTicket;
use PHPUnit\Framework\TestCase;


class OrderTicketTest extends TestCase
{

    /**
     * @var\Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(EntityManager $em) {
      $this->em = $em;
    }

    public function testaddTicket()

    //public function addTicket(Ticket $ticket): self

    {
        $ticket = new Ticket;
        //$ticket->getFullDay() = 0;

        $ticket = new Ticket();

        $admission = $this->em->getRepository(Admission::class)->findOneBy(['constant_key'=>"ADULT_PRICE"]);
        
        $ticket->setAdmission($admission);
        //$ticket->setDateEntry($this->session->get('dateEntry'));
        $ticket->setFullDay(1);

        $order->addTicket($ticket);

        $orderTicket = new OrderTicket(1, 10, 1);
        
        $result = $orderTicket->addTicket($ticket);

        
        $this->assertSame(10, $result);




      //  $result= $product->computeTVA());
      //  $this->assertSame(1.1, $result);
      //  $this->assertSame(1.1, $product->computeTVA());
    }
}  
    