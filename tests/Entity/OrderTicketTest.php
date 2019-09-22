<?php

namespace Tests\Entity;

use App\Entity\Ticket;
use App\Entity\Admission;
use App\Entity\OrderTicket;
use PHPUnit\Framework\TestCase;


class OrderTicketTest extends TestCase
{

    public function testaddTicket()

    {

        $admission = new Admission;
        $admission->setAmount(16);

        $ticket1 = new Ticket;
        $ticket1->setFullDay(1);
        $ticket1->setAdmission($admission);

        $ticket2 = new Ticket;
        $ticket2->setFullDay(1);

        $ticket2->setAdmission($admission);
        
        
        $order = new OrderTicket();
           
        $order->addTicket($ticket1);
        $order->addTicket($ticket2);

        $result=$order->getPrice();

        $this->assertSame(32, $result);

    }
}  
    