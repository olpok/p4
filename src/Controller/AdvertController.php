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

        return $this->render('advert/home.html.twig');
    }
   

    /**
     * @Route("/select", name="select")
     */
    public function select(Request $request, OrderManager $orderManager)
    {

      $defaultData = array('message' => 'myform');

      $form = $this->createFormBuilder($defaultData)
                    -> add ('dateEntry', DateType::class, 
                    ['label' => 'Date d\'entrée'
                    ]) 
                    -> add ('email', EmailType::class,)
                    -> add ('fullDay', ChoiceType::class, ['label' => 'Type','choices' => 
                        ['Journée' => true,
                        'Demi-journée' => false]
                    ])
                    -> add ('adultAdmission', IntegerType::class, ['label' => 'Nombre d\'entrées', 'attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-adult',  'min' => '0', 'max' => '20')])                   
                    -> add ('seniorAdmission', IntegerType::class, ['label' => 'Nombre d\'entrées','attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-senior', 'min' => '0', 'max' => '20')])
                    -> add ('childAdmission', IntegerType::class, ['label' => 'Nombre d\'entrées', 'attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-child', 'min' => '0', 'max' => '20')])  
                    -> add ('lowPriceAdmission', IntegerType::class, ['label' => 'Nombre d\'entrées','attr' => array('class' => 'admissionItem', 'id' => 'admissionItem-lowPrice', 'min' => '0', 'max' => '20')])
                    -> getForm ();  

                    

      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) 
    
        {
            $data = $form->getData();

            if(!$orderManager->beginOrder($data))
            {
                $this->addFlash("error","La date doit être ultérieure à aujourd'hui");
                return $this->redirectToRoute('select');
            }
            return $this->redirectToRoute('step2');
        }

        return $this->render('advert/select.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
    
    /**
     * @Route("/step2", name="step2")
     */
    public function step2(Request $request, ObjectManager $manager, Session $session, 
    OrderManager $orderManager)
    
    {
        
        $em=$this->getDoctrine()->getManager();

        $order = $orderManager->createOrder();

        $form= $this->createForm(OrderTicketType::class, $order); 
        $form->handleRequest($request);
      
        if($form->isSubmitted() && $form->isValid())   
        {
            $manager->persist($order);
            $manager->flush();

            return $this->redirectToRoute('step3', array('orderId' => $order->getid()));
        } 
        
        return $this->render('advert/step2.html.twig', [
        'form' => $form->createView(), 
        ]);

    }

    /**
     * @Route("/step3/{orderId}", name="step3")
     */
    public function step3($orderId)
    {
        $orderTicket =  $this->getDoctrine()->getManager()->getRepository(OrderTicket::class)->find($orderId);

        return $this->render('advert/step3.html.twig', array('order' => $orderTicket));
    } 

    /**
     * @Route(
     *     "/charge",
     *     name="charge",
     *     methods="POST"
     * )
     */
    public function checkoutAction(Request $request, \Swift_Mailer $mailer)
    {
        \Stripe\Stripe::setApiKey("sk_test_DJhgWO9m8Fu2aXsUTVQEwnWJ00kfk8QKBu");

        // Get the credit card details submitted by the form
        $token = $request->get('stripeToken');

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => 1000, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));

            $orderId=$request->get ('orderId');
            $orderTicket =$this->getDoctrine()->getManager()->getRepository(OrderTicket::class)->find($orderId);

            // send mail

                $message = (new \Swift_Message('Validation de votre commande'))
                ->setFrom('send@example.com')
                ->setTo('recipient@example.com')
                ->setBody(
                  $this->renderView(
                     'emails/confirmation.html.twig',
                      array('order' => $orderTicket) 
                  ),
                  'text/html'
                )
                ->addPart(
                    $this->renderView(
                        'emails/confirmation.txt.twig',
                         array('order' => $orderTicket)
                    ),
                    'text/plain'
                )
                ;

                $mailer->send($message);

                $this->addFlash('success','Félicitations !');
                
                return $this->render('advert/step4.html.twig',
                                array('order' => $orderTicket)
            );
            } 
        
        catch(\Stripe\Error\Card $e) {

            $this->addFlash('error','Erreur! Recommencez la transaction.');
            return $this->redirectToRoute("step3");
            // The card has been declined
        }
    }

       
    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('advert/contact.html.twig');
    } 

    /**
     * @Route("/step4", name="step4")
     */
    public function step4()

    {
        return $this->render('advert/step4.html.twig');
    } 

     
    /**
     * @Route("/mentionslegales", name="mentionslegales")
     */
    public function mentionslegales()
    {
        return $this->render('advert/mentionslegales.html.twig');
    } 

}
