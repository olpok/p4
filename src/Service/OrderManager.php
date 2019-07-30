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

    public function __construct(SessionInterface $session, EntityManagerInterface $em )
    {
        $this->session = $session;
        $this->em = $em;
    }
    public function beginOrder(){
/*
        $defaultData = array('message' => 'myform');

        $form = $this -> createFormBuilder($defaultData)
                      -> add ('dateEntry', DateType::class) 
                      -> add ('email', EmailType::class)
                      -> add ('fullDay', ChoiceType::class, ['choices' => 
                          ['Journée' => true,
                          'Demi-journée' => false]
                      ])
                      -> add ('adultAdmission', IntegerType::class, array('attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-adult', 'constraints' => 'PositiveOrZero')))                   
                      -> add ('seniorAdmission', IntegerType::class, array('attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-senior')))
                      -> add ('childAdmission', IntegerType::class, array('attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-child')))  
                      -> add ('lowPriceAdmission', IntegerType::class, array('attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-lowPrice')))
                      -> getForm ();                       
          
  
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) */
  
        {
                $data = $form->getData();
                $this->session->set('dateEntry', $data['dateEntry']);
                $this->session->set('email', $data['email']);
                $this->session->set('fullDay', $data['fullDay']);//boolean
                $this->session->set('nbAdultTicket', $data['adultAdmission']); // ajouter la classe pour le CSS
                $this->session->set('nbSeniorTicket', $data['seniorAdmission']);
                $this->session->set('nbChildTicket', $data['childAdmission']);
                $this->session->set('nbLowPriceTicket', $data['lowPriceAdmission']);

                return $this->redirectToRoute('step2');
        
        } return $this->render('advert/select.html.twig', [
            'form' => $form->createView()
        ]);
      
    }

   




    public function createOrder()
    {
        
        $order = new OrderTicket();
                
        $order->setEmail($this->session->get('email'));

        $admissions = array(
                                'ADULT_PRICE' => 'nbAdultTicket',
                                'SENIOR_PRICE' => 'nbSeniorTicket',
                                'CHILD_PRICE' => 'nbChildTicket'
        );

        foreach($admissions as $constant => $sessionNbKey) {
            $admission = $this->em->getRepository(Admission::class)->findOneBy(['constant_key'=>$constant]);
            for($i = 0; $i < $this->session->get($sessionNbKey); $i++) 
            {
                $ticket = new Ticket();
                $ticket->setAdmission($admission);
                $ticket->setDateEntry($this->session->get('dateEntry'));
                $order->addTicket($ticket);
            }
        }

        return $order;
    }
}