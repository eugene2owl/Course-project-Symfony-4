<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\Quiz;
use App\Entity\Question;
use App\Entity\Result;
use App\Entity\User;
use App\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuizDrawer extends Controller
{
    public function findOutToResultLink(Quiz $currentQuiz, int $nextQuestionNumber, string $slug, Controller $controller): string
    {
        $toResultsLink = "";
        if (!(count($currentQuiz->getQuestionList()) > $nextQuestionNumber)) {
            $toResultsLink = $controller->generateUrl('QuizResults', ['slug'=>$slug]);
        }
        return $toResultsLink;
    }

    public function findOutQuestionText($question): string
    {
        $questionText = '';
        if ($question != null) {
            $questionText = $question->getText();
        }
        return $questionText;
    }

    public function findOutAnswers($question): array
    {
        $answers = [];
        if ($question != null) {
            foreach ($question->getAnswerList() as $currentAnswer) {
                array_push($answers, $currentAnswer->getText());
            }
        }
        return $answers;
    }

    public function findOutCurrentQuiz(string $name, Controller $controller): Quiz
    {
        $em = $controller->getDoctrine()->getManager();
        $criteria = array('quizname' => $name);
        return $em->getRepository(Quiz::class)->findOneBy($criteria);
    }

    public function findOutCurrentQuestionPage(Quiz $quiz, Controller $controller): int
    {
        $user = $controller->getUser();
        $em = $controller->getDoctrine()->getManager();
        $criteria = ['user' => $user->getId(), 'quiz' => $quiz->getId()];
        $result = $em->getRepository(Result::class)->findBy($criteria);
        $CurrentQuestionPage = 1;
        if (count($result) < count($quiz->getQuestionList())) {
            $CurrentQuestionPage = count($result) + 1;
        } else {
            $this->deleteAllOldResults($user, $quiz, $controller);
        }
        return $CurrentQuestionPage;
    }

    public function addNewPlayer(Quiz $quiz, User $user, Controller $controller): Quiz
    {
        $em = $controller->getDoctrine()->getManager();
        $criteria = ['user' => $user->getId(), 'quiz' => $quiz->getId()];
        $result = $em->getRepository(Result::class)->findBy($criteria);
        count($result) != 0 ? $userAlreadyExist = true : $userAlreadyExist = false;
        if (!$userAlreadyExist) {
            $quiz->setPlayersAmount($quiz->getPlayersAmount() + 1);
        }
        return $quiz;
    }

    public function deleteAllOldResults(User $user, Quiz $quiz, Controller $controller): void
    {
        $em = $controller->getDoctrine()->getManager();
        $criteria = ['user'=>$user->getId(), 'quiz'=>$quiz->getId()];
        $res = $controller->getDoctrine()->getRepository(Result::class)->findBy($criteria);
        foreach ($res as $r) {
            $em->remove($r);
        }
        $em->flush();
    }

    public function recognizeResult(Quiz $currentQuiz, User $user, Question $question, Answer $answer, Controller $controller): void
    {
        $result = $this->createNewOrReplaceExistingResult($currentQuiz, $user, $question, $controller);
        $result->setQuiz($currentQuiz);
        $currentQuiz->setResultList($result);
        $result->setUser($user);
        $user->setResultList($result);
        $result->setQuestion($question);
        $question->setResultList($result);
        $result->setAnswer($answer);
        $answer->setResultList($result);
        $em = $controller->getDoctrine()->getManager();
        $em->persist($currentQuiz);
        $em->persist($user);
        $em->persist($question);
        $em->persist($answer);
        $em->persist($result);
        $em->flush();
    }

    public function returnObjectAnswerByName(string $numberAnswer, Quiz $quiz, int $questionNumber): Answer
    {
        $result = null;
        $answersArray = $quiz->getQuestionList()[$questionNumber - 1]->getAnswerList();
        foreach ($answersArray as $index => $answer) {
            if ($index == (int)$numberAnswer - 1) {
                $result = $answer;
            };
        }
        return $result;
    }

    public function createNewOrReplaceExistingResult(Quiz $currentQuiz, User $user, Question $question, Controller $controller): Result
    {
        $repository = $controller->getDoctrine()->getRepository(Result::class);
        $resultsArray = $repository->findAll();
        for ($resultNumber = 0; $resultNumber < count($resultsArray); $resultNumber++) {
            if ($resultsArray[$resultNumber]->getQuiz() == $currentQuiz &&
                $resultsArray[$resultNumber]->getQuestion() == $question &&
                $resultsArray[$resultNumber]->getUser() == $user
            ) {
                $neededResult = $resultsArray[$resultNumber];
            }
        }
        if (!isset($neededResult)) {
            $neededResult = new Result();
        }
        return $neededResult;
    }
}