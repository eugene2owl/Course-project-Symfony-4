<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ResultsCalculator extends Controller
{
    public function findOutCurrentQuiz(string $name, Controller $controller): Quiz
    {
        $em = $controller->getDoctrine()->getManager();
        $criteria = array('quizname' => $name);
        return $em->getRepository(Quiz::class)->findOneBy($criteria);
    }

    public function findOutCurrentQuizResults(Quiz $quiz, Controller $controller): array
    {
        $em = $controller->getDoctrine()->getManager();
        $criteria = array('quiz' => $quiz->getId());
        return $em->getRepository(Result::class)->findBy($criteria);
    }

    public function sortPlayers(array $players): array
    {
        uasort($players, function ($a, $b) {
            return $b - $a;
        });
        return $players;
    }

    public function getUsersOfCurrentQuiz(array $currentQuizResults, Controller $controller): array
    {
        $arrayUser_correctAnswersAmount = [];
        foreach ($currentQuizResults as $result) {
            $currentUser = $result->getUser()->getUsername();
            if ($this->didUserPassQuiz($result->getUser(), $result->getQuiz(), $controller)) {
                $arrayUser_correctAnswersAmount[$currentUser] = 0;
            }
        }
        foreach ($currentQuizResults as $result) {
            $currentUser = $result->getUser()->getUsername();
            if ($this->didUserPassQuiz($result->getUser(), $result->getQuiz(), $controller)) {
                $currentResultCorrectness = $result->getAnswer()->getisTrue();
                if ($currentResultCorrectness) {
                    $arrayUser_correctAnswersAmount[$currentUser]++;
                }
            }
        }
        return $arrayUser_correctAnswersAmount;
    }

    private function didUserPassQuiz(User $user, Quiz $quiz, Controller $controller): bool
    {
        $em = $controller->getDoctrine()->getManager();
        $criteria = array('quiz' => $quiz->getId(), 'user' => $user->getId());
        $passedQuestionsOfCurrentUser = $em->getRepository(Result::class)->findBy($criteria);
        count($passedQuestionsOfCurrentUser) == count($quiz->getQuestionList()) ? $didPass = true : $didPass = false;
        return $didPass;
    }

    public function findOutUserPlace(array $arrayUser_correctAnswersAmount, Controller $controller): int
    {
        $username = $controller->getUser()->getUsername();
        foreach (array_keys($arrayUser_correctAnswersAmount) as $key => $value) {
            if ($value == $username) {
                return $key + 1;
            }
        }
        return -1;
    }

    public function findOutBestPlayersCities(array $arrayUser_correctAnswersAmount, Controller $controller): array
    {
        $em = $controller->getDoctrine()->getManager();

        $bestPlayersCities = [];
        foreach (array_keys($arrayUser_correctAnswersAmount) as $number => $username) {
            if ($number < 4) {
                $criteria = array('username' => $username);
                $arr = $em->getRepository(User::class)->findBy($criteria);
                array_push($bestPlayersCities, $arr[0]->getcity());
            }
        }
        return $bestPlayersCities;
    }

    public function recognizeBestPlayer(array $arrayUser_correctAnswersAmount, Quiz $quiz, Controller $controller): void
    {
        $em = $controller->getDoctrine()->getManager();

        $username = array_keys($arrayUser_correctAnswersAmount)[0];
        $criteria = array('username' => $username);
        $arr = $em->getRepository(User::class)->findBy($criteria);
        $user = $arr[0];

        $quiz->setFirstNameLider($user->getFirstname());
        $quiz->setSecondNameLider($user->getSecondname());
        $quiz->setThirdNameLider($user->getThirdname());

        $em->persist($quiz);
        $em->flush();
    }
}