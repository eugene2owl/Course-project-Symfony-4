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

class QuizeController extends Controller
{
    /**
     * @Route("/main/quizzes/{slug}",
     *     name="quizStart",
     *     )
     */
    public function quizStart(Request $request,string $slug): RedirectResponse
    {
        $currentQuestionNumber = 1;
        return new RedirectResponse($slug."/".$currentQuestionNumber);
    }

    /**
     * @Route("/main/quizzes/{slug}/{number}",
     *     name="currentQuestionPage",
     *     )
     */
    public function ShowCurrentQuestionPage(Request $request,string $slug, int $number)
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);
        $arr = $repository->findBy(['quizname' => $slug]);

        $currentQuiz = $arr[0];
        $currentUser = $this->getUser();
        $currentQuiz = $this->addNewPlayer($currentQuiz, $currentUser);

        $nextQuestionLink = $this->generateUrl('currentQuestionPage', ['slug'=>$slug, 'number' => $number+1]);

        $currentQuestion = $currentQuiz->getQuestionList()[$number - 2];

        $answerText = $request->get('select');
        if ($answerText != null && $currentQuestion != null) {
            $answerObj = $this->returnObjectAnswerByName($answerText, $currentQuiz, $number - 1);
            $this->recognizeResult($currentQuiz, $currentUser, $currentQuestion, $answerObj);
        }

        if (count($currentQuiz->getQuestionList()) > $number - 1) {

            return $this->render('Quizzes/quiz.html.twig', array(
                'currentQuestionNumber' => $number,
                'quiz' => $currentQuiz,
                'nextQuestionLink' => $nextQuestionLink,
            ));
        } else {
            $toResultsLink = $this->generateUrl('QuizResults', ['slug'=>$slug]);
            return new RedirectResponse($toResultsLink);
        }
    }

    private function addNewPlayer(Quiz $quiz, User $user): Quiz
    {
        $userAlreadyExist = false;
        $repository = $this->getDoctrine()->getRepository(Result::class);
        $resultsArray = $repository->findAll();
        for ($resultNumber = 0; $resultNumber < count($resultsArray); $resultNumber++) {
            if ($resultsArray[$resultNumber]->getQuiz() == $quiz &&
                $resultsArray[$resultNumber]->getUser() == $user
            ) {
                $userAlreadyExist = true;
            }
        }
        if (!$userAlreadyExist) {
            $quiz->setPlayersAmount($quiz->getPlayersAmount() + 1);
        }
        return $quiz;
    }

    private function recognizeResult(Quiz $currentQuiz, User $user, Question $question, Answer $answer): void
    {
        $result = $this->createNewOrReplaceExistingResult($currentQuiz, $user, $question);


        $result->setQuiz($currentQuiz);
        $currentQuiz->setResultList($result);

        $result->setUser($user);
        $user->setResultList($result);

        $result->setQuestion($question);
        $question->setResultList($result);

        $result->setAnswer($answer);
        $answer->setResultList($result);

        $em = $this->getDoctrine()->getManager();

        $em->persist($currentQuiz);
        $em->persist($user);
        $em->persist($question);
        $em->persist($answer);
        $em->persist($result);
        $em->flush();
    }

    function returnObjectAnswerByName(string $textAnswer, Quiz $quiz, int $questionNumber): Answer
    {
        $result = null;
        $answersArray = $quiz->getQuestionList()[$questionNumber-1]->getAnswerList();
        for ($questionNumber = 0; $questionNumber < count($answersArray); $questionNumber++) {
            if ($answersArray[$questionNumber]->getText() == $textAnswer) {
                $result = $answersArray[$questionNumber];
                break;
            }
        }
        return $result;
    }

    function createNewOrReplaceExistingResult(Quiz $currentQuiz, User $user, Question $question): Result
    {
        $repository = $this->getDoctrine()->getRepository(Result::class);
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