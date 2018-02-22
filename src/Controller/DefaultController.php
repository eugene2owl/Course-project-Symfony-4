<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
     /**
      * @Route("/")
      */
    public function index()
    {
        $toLoginLink = $this->generateUrl('login');
        $toRegistrationLink = $this->generateUrl('user_registration');
        return $this->render('security/home.html.twig', [
            'toLoginLink' => $toLoginLink,
            'toRegistrationLink' => $toRegistrationLink,
        ]);
    }
}
