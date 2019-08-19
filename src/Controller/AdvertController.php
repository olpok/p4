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
    public function select(Request $request, OrderManager $orderManager)
    {

      $defaultData = array('message' => 'myform');

      $form = $this->createFormBuilder($defaultData)
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
    
        {
            $data = $form->getData();

            $orderManager->beginOrder($data);
           // if(val == 404) ....

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
            echo 'is valid';
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
        $orderTicket =  
        $em=$this->getDoctrine()->getManager()->getRepository(OrderTicket::class)->find($orderId);
        return $this->render('advert/step3.html.twig', array('order' => $orderTicket));
    } 

    /**
     * @Route(
     *     "/charge",
     *     name="charge",
     *     methods="POST"
     * )
     */
    public function checkoutAction(Request $request)
    {
        \Stripe\Stripe::setApiKey("sk_test_DJhgWO9m8Fu2aXsUTVQEwnWJ00kfk8QKBu");

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => 1000, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe - OpenClassrooms Exemple"
            ));
            $this->addFlash('success','Bravo ça marche !');
            return $this->redirectToRoute("step4");
            } 
        
        catch(\Stripe\Error\Card $e) {

            $this->addFlash("error","Snif ça marche pas :(");
            return $this->redirectToRoute("step3");
            // The card has been declined
        }
    }

    
    /**
     * @Route("/step4", name="step4")
     */
    public function step4()
    {
        return $this->render('advert/step4.html.twig');
    } 

    
    /**
     * @Route("/advert", name="advert")
     */
    public function sendEmail(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody('You should see me from the profiler!')
               // $this->renderView(
                    // templates/emails/registration.html.twig
               //     'emails/registration.html.twig'//,
                    //['name' => $name]
                
               //'text/html'
            

            // you can remove the following code if you don't define a text version for your emails
         //   ->addPart(
         //       $this->renderView(
         //           'emails/registration.txt.twig'//,
                   // ['name' => $name]
         //       ),
         //       'text/plain'
         //   )
        ;

        $mailer->send($message);

        return $this->render('advert/index.html.twig', [
                     'controller_name' => 'AdvertController',
                 ]);
       // return $this->render(...);
    }

 //   /**
 //    * @Route("/advert", name="advert")
  //   */
 //   public function index()
//   {
  //      return $this->render('advert/index.html.twig', [
  //          'controller_name' => 'AdvertController',
  //      ]);
  //  }  

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
