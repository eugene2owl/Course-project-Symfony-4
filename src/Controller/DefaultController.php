<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
     /**
      * @Route("/")
      */
    public function index()
    {
        return $this->render('security/home.html.twig');
    }

    /**
     * @Route("/simple")
     *
     * @return Response
     */
    public function simple()
    {
        return new Response('Simple! Easy! Great!');
    }

}