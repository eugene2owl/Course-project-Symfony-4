<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 23.02.18
 * Time: 16:46
 */

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SendController extends Controller
{
    /**
     * @Route("/mail", name="mail")
     */
    public function indexAction(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('quizgameeugeneproduction@gmail.com')
            ->setTo('eugenevaleska@gmail.com')
            ->setBody(
                $this->render(
                // templates/emails/registration.html.twig
                    'Mail/mail.html.twig'
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $mailer->send($message);

        $toLoginLink = $this->generateUrl('login');
        $toRegistrationLink = $this->generateUrl('user_registration');
        return $this->render('security/home.html.twig', [
            'toLoginLink' => $toLoginLink,
            'toRegistrationLink' => $toRegistrationLink,
        ]);
    }
}