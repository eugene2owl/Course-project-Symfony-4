<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ResultsCalculator;

class ResultsController extends Controller
{
    /**
     * @Route("/main/quizResults/{slug}",
     *     name="QuizResults",
     *     )
     */
    public function showQuizResults(string $slug, ResultsCalculator $resultsCalculator)
    {
        $currentQuiz = $resultsCalculator->findOutCurrentQuiz($slug, $this);
        $questionsAmount = count($currentQuiz->getQuestionList());
        $currentQuizResults = $resultsCalculator->findOutCurrentQuizResults($currentQuiz, $this);

        $arrayUser_correctAnswersAmount = $resultsCalculator->sortPlayers($resultsCalculator->getUsersOfCurrentQuiz($currentQuizResults, $this));

        $userPlace = $resultsCalculator->findOutUserPlace($arrayUser_correctAnswersAmount, $this);
        $bestPlayersCities = $resultsCalculator->findOutBestPlayersCities($arrayUser_correctAnswersAmount, $this);

        $toMainLink = $this->generateUrl('main');

        $resultsCalculator->recognizeBestPlayer($arrayUser_correctAnswersAmount, $currentQuiz, $this);

        return $this->render('Quizzes/quizResult.html.twig', [
            'quizName' => $currentQuiz->getQuizName(),
            'questionsAmount' => $questionsAmount,
            'currentQuizResults' => $currentQuizResults,
            'assocArray' => $arrayUser_correctAnswersAmount,
            'amountOfPlayers' => count($arrayUser_correctAnswersAmount),
            'userPlace' => $userPlace,
            'bestPlayersCities' => $bestPlayersCities,
            'toMainLink' => $toMainLink,
        ]);
    }
}
