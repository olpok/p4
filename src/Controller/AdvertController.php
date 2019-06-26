<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\OrderTicket;
use App\Form\OrderTicketType;
use App\Entity\Ticket\Admission;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function select(Request $request){

      $defaultData = array('message' => 'myform');

      $form = $this -> createFormBuilder($defaultData)
                    -> add ('dateEntry', DateType::class) 
                    -> add ('email')
                    -> add ('fullDay', ChoiceType::class, ['choices' => 
                        ['Journée' => true,
                        'Demi-journée' => false]
                    ])
                 // -> add ('adultPrice', MoneyType::class)
                    -> add ('adultAdmission', IntegerType::class, array('attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-adult')))                   
                    -> add ('seniorAdmission', IntegerType::class, array('attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-senior')))
                    -> add ('childAdmission', IntegerType::class, array('attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-child')))  
                    -> add ('lowPriceAdmission', IntegerType::class, array('attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-lowPrice')))
                    -> getForm ();                       
        

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
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
    public function step2(Request $request, ObjectManager $manager){
        
        $order = new OrderTicket();
        $order->setEmail($this->session('email'));

        // ticket audlte
        for($i = 0; $î < $this->session->get('ndAdultTicket'); $î++) {
            $ticket = new Ticket(); // cree par n ticket adulte
            $order->addTicket($ticket);
        }
      


        dd($order); // dump and die

        $form = $this -> createFormBuilder($order)
                      -> add ('dateOrder', DateType:: class) 
                     // -> add ('firstName')
                      -> add ('price')
                      -> getForm ();

    //$ticket = new Ticket();

    //$form = $this -> createFormBuilder($ticket)
    //              -> add ('dateEntry', DateType:: class) 
    //              -> add ('fullDay')
    //              -> getForm ();
    
    
    $form->handleRequest($request);
    //dump($ticket);

    return $this->render('advert/step2.html.twig', [
        'formOrder' => $form->createView()
    ]);
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

    /**
     * @Route("/select_old", name="select_old")
     */
    public function select_old(Request $request, ObjectManager $manager){

        $order = new OrderTicket();

      $form = $this -> createFormBuilder($order)
                    -> add ('dateOrder', DateType:: class) 
                    -> add ('email')
                    -> add ('price')
                    -> getForm ();
                       
    //    $form= $this->createForm(OrderTicketType::class, $order);    

        $form->handleRequest($request);
        dump($order);
    //    if($form->isSubmitted() && $form->isValid()) 
    //    {
    //        $manager->persist($order);
    //        $manager->flush();

    //        return $this->redirectToRoute('advert');
    //    }

        return $this->render('advert/select.html.twig', [
            'formOrder' => $form->createView()
        ]);
    }
//  public function startOrder(Request $request, Session $session)
// 	{
//	    $datas = $form->getData();
//		$this->session->set('date_entry', $datas['date_entry']);
//		$ticketsArray = [	
//								'fullday' => ['ADULT' => 2, 'CHILD' => 3], 
//								'halfday' => ['ADULT' => 2, 'CHILD' => 3, 'SENIOR' => 1]																																			];
								
//		$this->session->set('tickets', $ticketsArray);
				
//		$this->redirect('step2');
//	}


}
