<?php

declare(strict_types = 1);

namespace App\Service;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Registrator extends Controller
{
    public function sendMail(\Swift_Mailer $mailer, User $user, string $toMainLink, Controller $controller)
    {
        $message = (new \Swift_Message('Welcome to Quiz Competition'))
            ->setFrom('quizgameeugeneproduction@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $controller->render(
                    'Mail/mail.html.twig',
                    [
                        'toMainLink' => $toMainLink,
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);
    }
}