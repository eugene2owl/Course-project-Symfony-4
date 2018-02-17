<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResultsController extends Controller
{
    /**
     * @Route("/main/quizResults/{slug}",
     *     name="QuizResults",
     *     )
     */
    public function ShowQuizResults(Request $request, $slug)
    {
        $repository = $this->getDoctrine()->getRepository(Result::class);
        $resultsArray = $repository->findAll();
        return $this->render('Quizzes/quizResult.html.twig', array(
            'quizName' => $slug,
        ));
    }

    private function getUsersOfCurrentQuiz()
    {

    }

    private function getCorrectAnswersArray()
    {

    }

    private function sortByAmountOfCorrectAnswers()
    {

    }
}