<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\Quiz;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlayersRightsCalculator extends Controller
{
    public function findPassedQuizzes(array $quizArray, Controller $controller): array
    {
        $enabledArray = [];
        for ($currentQuiz = 0; $currentQuiz < count($quizArray); $currentQuiz++) {
            $isPassed = false;
            $amountOfPassedTasks = 0;
            foreach ($quizArray[$currentQuiz]->MYgetResultList() as $quizResult) {
                if ($quizResult->getUser() == $controller->getUser()) {
                    $amountOfPassedTasks++;
                }
            }
            if ($amountOfPassedTasks == count($quizArray[$currentQuiz]->getQuestionList())) {
                $isPassed = true;
            }
            $isPassed ? array_push($enabledArray, true) : array_push($enabledArray, false);
        }
        return $enabledArray;
    }

    public function findAllQuizzes(Controller $controller): array
    {
        $repository = $controller->getDoctrine()->getRepository(Quiz::class);
        return $repository->findAll();
    }
}