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
      *
      * @return Response
      */
    public function index()
    {
        return new Response("<html><h1 align='center'>You are on home page.</h1></html>");
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