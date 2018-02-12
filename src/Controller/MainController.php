<?php

namespace App\Controller;

use App\Entity\Quiz;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuizRepository;

class MainController extends Controller
{
    /**
     * @Route("/main", name="main")
     */
    public function mainFunction()
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);
        $arr = $repository->findAll();
        return $this->render('security/main.html.twig', array(
            'quizList' => $arr,
        ));
    }
}