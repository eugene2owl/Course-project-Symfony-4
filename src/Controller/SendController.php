<?php

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
        $toMainLink = $this->generateUrl('main');
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('quizgameeugeneproduction@gmail.com')
            ->setTo('eugenevaleska1994@gmail.com')
            ->setBody(
                $this->render(
                    'Mail/mail.html.twig',
                    [
                        'toMainLink' => 'http://symfony4.loc/main',
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);
        $toLoginLink = $this->generateUrl('login');
        $toRegistrationLink = $this->generateUrl('user_registration');
        return $this->render('security/home.html.twig', [
            'toLoginLink' => $toLoginLink,
            'toRegistrationLink' => $toRegistrationLink,
        ]);
    }
}