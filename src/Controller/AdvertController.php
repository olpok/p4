<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\OrderTicket;
use App\Form\OrderTicketType;
use App\Entity\Admission;
use App\Service\OrderManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdvertController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/", name="home")
     */
    public function home()
    {

        $email_in_session = $this->session->get('email');

        return $this->render('advert/home.html.twig', [
            'title' => "Hello!"
        ]);
    }

    

 /**
     * @Route("/select", name="select")
     */
    public function select(Request $request, OrderManager $orderManager){

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
      if($form->isSubmitted() && $form->isValid()) 

  /*    $order = $orderManager->beginOrder();*/
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
        }

        return $this->render('advert/select.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/step2", name="step2")
     */
    public function step2(Request $request, ObjectManager $manager, Session $session, OrderManager $orderManager){
        
        $em=$this->getDoctrine()->getManager();

        $order = $orderManager->createOrder();
/*
        $order = new OrderTicket();
        
        $order->setEmail($this->session->get('email'));


        // ticket adulte
        $Adultadmission=$em->getRepository(Admission::class)->findOneBy(['constant_key'=>"ADULT_PRICE"]);
        for($i = 0; $i < $this->session->get('nbAdultTicket'); $i++) 
       {
            $ticket = new Ticket(); // cree par n ticket adulte
            $ticket->setAdmission($Adultadmission);
            $ticket->setDateEntry($this->session->get('dateEntry'));
            $order->addTicket($ticket);
        }
        // ticket senior
        $Senioradmission=$em->getRepository(Admission::class)->findOneBy(['constant_key'=>"SENIOR_PRICE"]);
        for($j = 0; $j < $this->session->get('nbSeniorTicket'); $j++)
       {
            $ticket = new Ticket(); // cree par n ticket adulte
            $ticket->setAdmission($Senioradmission); 
            $order->addTicket($ticket);
        }
         // ticket child
        $Childadmission=$em->getRepository(Admission::class)->findOneBy(['constant_key'=>"CHILD_PRICE"]);
        for($k = 0; $k < $this->session->get('nbChildTicket'); $k++)
        {
            $ticket = new Ticket(); // cree par n ticket adulte
            $ticket->setAdmission($Childadmission);
            $order->addTicket($ticket);
        }*/

        $form= $this->createForm(OrderTicketType::class, $order); 
        $form->handleRequest($request);


      
        if($form->isSubmitted() && $form->isValid())   
        {

            echo 'is valid';
            $manager->persist($order);
   ;
            $manager->flush();
            return $this->redirectToRoute('step3');
        } else {
            echo 'no submit, no valid';
        }
        
    return $this->render('advert/step2.html.twig', [
        'form' => $form->createView(), 
    ]);

}

    /**
     * @Route("/step3", name="step3")
     */
    public function step3()
    {
        return $this->render('advert/step3.html.twig');
    } 

     /**
     * @Route("/prepare", name="prepare")
     */
    public function prepareAction()
    {
        return $this->render('advert/prepare.html.twig');
    }

    /**
     * @Route(
     *     "/checkout",
     *     name="checkout",
     *     methods="POST"
     * )
     */
    public function checkoutAction(Request $request)
    {
        //TODO : Cette action est à faire
    }

    /**
     * @Route("/advert", name="advert")
     */
    public function index()
    {
        return $this->render('advert/index.html.twig', [
            'controller_name' => 'AdvertController',
        ]);
    }  
    /**
     * @Route("/traduction/12", name="traduction")
     */
    public function translationAction()
    {
        return $this->render('advert/translation.html.twig', [
        'name' => 'winner'
        ]);
    }

    

}
