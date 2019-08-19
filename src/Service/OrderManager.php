<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\OrderTicket;
use App\Entity\Ticket;
use App\Entity\Admission;
use Doctrine\ORM\EntityManagerInterface;


class OrderManager
{

    private $session;
    private $em;

    private $admissions=array(
        'ADULT_PRICE' => 'nbAdultTicket',
        'SENIOR_PRICE' => 'nbSeniorTicket',
        'CHILD_PRICE' => 'nbChildTicket',
        'LOW_PRICE' => 'nbLowPriceTicket'
);
    public function __construct(SessionInterface $session, EntityManagerInterface $em )
    {
        $this->session = $session;
        $this->em = $em;
    }

    /**
     * insert in session datas from order
     * @param $data
     * @return int
     */
    public function beginOrder($data){

        $this->session->set('dateEntry', $data['dateEntry']);
        $this->session->set('email', $data['email']);
        $this->session->set('fullDay', $data['fullDay']);//boolean
        $this->session->set('nbAdultTicket', $data['adultAdmission']); // ajouter la classe pour le CSS
        $this->session->set('nbSeniorTicket', $data['seniorAdmission']);
        $this->session->set('nbChildTicket', $data['childAdmission']);
        $this->session->set('nbLowPriceTicket', $data['lowPriceAdmission']);

        return true;

    }

   
    /**
     * create an order
     * @return OrderTicket $order;
     */
    public function createOrder()
    {
        
        $order = new OrderTicket();
                
        $order->setEmail($this->session->get('email'));

        $admissions = $this->admissions;

        foreach($admissions as $constant => $sessionNbKey) {
            $admission = $this->em->getRepository(Admission::class)->findOneBy(['constant_key'=>$constant]);
            for($i = 0; $i < $this->session->get($sessionNbKey); $i++) 
            {
                $ticket = new Ticket();
                $ticket->setAdmission($admission);
                $ticket->setDateEntry($this->session->get('dateEntry'));
                $ticket->setFullDay($this->session->get('fullDay'));
                $order->addTicket($ticket);
            }
        }

        return $order;
    }
}