<?php

declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\QuizCreator;

class QuizCreateController extends Controller
{
    /**
     * @Route("/createQuizQuestionAnswersTie", name="createQuiz")
     */
    public function index(QuizCreator $quizCreator)
    {
        $quizCreator->createQuiz($this);
        return new Response();
    }
}
