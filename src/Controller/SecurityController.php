<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        if ($this->getUser() != null) {
            $toMainLink = $this->generateUrl('main');
            return $this->redirect($toMainLink);
        }
        session_reset();
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        $toRegistrationLink = $this->generateUrl('user_registration');
        return $this->render('security/login.html.twig', array(
            'toRegistrationLink' => $toRegistrationLink,
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}
