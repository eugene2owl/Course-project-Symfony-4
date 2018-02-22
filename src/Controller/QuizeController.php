<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
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
    public function quizStart(string $slug)
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);
        $arr = $repository->findBy(['quizname' => $slug]);
        $currentQuiz = $arr[0];

        define('CURRENT_QUESTION_PAGE', $this->findOutCurrentQuestionPage($currentQuiz));

        $questionText = $currentQuiz->getQuestionList()[CURRENT_QUESTION_PAGE - 1]->getText();
        $answersAmount = count($currentQuiz->getQuestionList()[CURRENT_QUESTION_PAGE - 1]->getAnswerList());
        $nextQuestionLink = $this->generateUrl('nextQuestion', ['slug'=>$slug, 'number' => CURRENT_QUESTION_PAGE]);
        $currentQuestion = $currentQuiz->getQuestionList()[CURRENT_QUESTION_PAGE - 1];
        $answers = [];
        if ($currentQuestion != null) {
            foreach ($currentQuestion->getAnswerList() as $currentAnswer) {
                array_push($answers, $currentAnswer->getText());
            }
        }
        return $this->render('Quizzes/quiz.html.twig', array(
            'answers' => $answers,
            'currentQuestionNumber' => CURRENT_QUESTION_PAGE,
            'quiz' => $currentQuiz,
            'questionText' => $questionText,
            'nextQuestionLink' => $nextQuestionLink,
            'answersAmount' => $answersAmount,
        ));
    }

    private function findOutCurrentQuestionPage(Quiz $quiz): int
    {
        $em = $this->getDoctrine()->getManager();
        $criteria = array('user' => $this->getUser()->getId(), 'quiz' => $quiz->getId());
        $result = $em->getRepository(Result::class)->findBy($criteria);
        $CurrentQuestionPage = 1;
        if (count($result) < count($quiz->getQuestionList())) {
            $CurrentQuestionPage = count($result) + 1;
        }
        return $CurrentQuestionPage;
    }

    /**
     * @Route("/main/quizzes/{slug}/ajax/{number}",
     *     name="nextQuestion",
     *     )
     */
    public function showCurrentQuestionPage(Request $request, string $slug, int $number)
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);
        $arr = $repository->findBy(['quizname' => $slug]);
        $currentQuiz = $arr[0];
        $nextQuestionLink = $this->generateUrl('nextQuestion', ['slug'=>$slug, 'number' =>$number+1]);
        $nextQuestionNumber = $number;
        $nextQuestion = $currentQuiz->getQuestionList()[$nextQuestionNumber];
        $currentUser = $this->getUser();
        $currentQuiz = $this->addNewPlayer($currentQuiz, $currentUser);
        $answers = [];
        if ($nextQuestion != null) {
            foreach ($nextQuestion->getAnswerList() as $currentAnswer) {
                array_push($answers, $currentAnswer->getText());
            }
        }
        $numberAnswer = $request->get('select');
        $answerObj = $this->returnObjectAnswerByName($numberAnswer, $currentQuiz, $number);
        $currentQuestion = $currentQuiz->getQuestionList()[$nextQuestionNumber - 1];
        $this->recognizeResult($currentQuiz, $currentUser, $currentQuestion, $answerObj);
        $nextQuestionText = '';
        if ($nextQuestion != null) {
            $nextQuestionText = $nextQuestion->getText();
        }
        $response_data = array(
            'number' => $number + 1,
            'nextQuestion' => $nextQuestionText,
            'nextQuestionLink' => $nextQuestionLink,
            'answers' => $answers,
            'correctness' => $answerObj->getisTrue(),
            'toResultLink' => "",
        );
        if (!(count($currentQuiz->getQuestionList()) > $nextQuestionNumber)) {
            $toResultsLink = $this->generateUrl('QuizResults', ['slug'=>$slug]);
            $response_data['toResultLink'] = $toResultsLink;
        }
        return $this->json($response_data);
    }

    private function addNewPlayer(Quiz $quiz, User $user): Quiz
    {
        $em = $this->getDoctrine()->getManager();
        $criteria = array('user' => $user->getId(), 'quiz' => $quiz->getId());
        $result = $em->getRepository(Result::class)->findBy($criteria);
        count($result) != 0 ? $userAlreadyExist = true : $userAlreadyExist = false;
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

    private function returnObjectAnswerByName(string $numberAnswer, Quiz $quiz, int $questionNumber): Answer
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

    private function createNewOrReplaceExistingResult(Quiz $currentQuiz, User $user, Question $question): Result
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
