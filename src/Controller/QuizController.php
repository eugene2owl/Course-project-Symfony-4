<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\QuizDrawer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{
    /**
     * @Route("/main/quizzes/{slug}",
     *     name="quizStart",
     *     )
     */
    public function quizStart(string $slug, QuizDrawer $drawer)
    {
        $currentQuiz = $drawer->findOutCurrentQuiz($slug, $this);

        define('CURRENT_QUESTION_PAGE', $drawer->findOutCurrentQuestionPage($currentQuiz, $this));

        $questionText = $currentQuiz->getQuestionList()[CURRENT_QUESTION_PAGE - 1]->getText();
        $answersAmount = count($currentQuiz->getQuestionList()[CURRENT_QUESTION_PAGE - 1]->getAnswerList());
        $nextQuestionLink = $this->generateUrl('nextQuestion', ['slug'=>$slug, 'number' => CURRENT_QUESTION_PAGE]);
        $currentQuestion = $currentQuiz->getQuestionList()[CURRENT_QUESTION_PAGE - 1];
        $answers = $drawer->findOutAnswers($currentQuestion);
        return $this->render('Quizzes/quiz.html.twig', array(
            'answers' => $answers,
            'currentQuestionNumber' => CURRENT_QUESTION_PAGE,
            'quiz' => $currentQuiz,
            'questionText' => $questionText,
            'nextQuestionLink' => $nextQuestionLink,
            'answersAmount' => $answersAmount,
        ));
    }

    /**
     * @Route("/main/quizzes/{slug}/ajax/{number}",
     *     name="nextQuestion",
     *     )
     */
    public function showCurrentQuestionPage(Request $request, string $slug, int $number, QuizDrawer $drawer)
    {
        $currentQuiz = $drawer->findOutCurrentQuiz($slug, $this);
        $nextQuestionLink = $this->generateUrl('nextQuestion', ['slug'=>$slug, 'number' =>$number+1]);
        $nextQuestionNumber = $number;
        $nextQuestion = $currentQuiz->getQuestionList()[$nextQuestionNumber];
        $currentUser = $this->getUser();
        $currentQuiz = $drawer->addNewPlayer($currentQuiz, $currentUser, $this);
        $answers = $drawer->findOutAnswers($nextQuestion);
        $numberAnswer = $request->get('select');
        $answerObj = $drawer->returnObjectAnswerByName($numberAnswer, $currentQuiz, $number);
        $currentQuestion = $currentQuiz->getQuestionList()[$nextQuestionNumber - 1];
        $nextQuestionText = $drawer->findOutQuestionText($nextQuestion);
        $drawer->recognizeResult($currentQuiz, $currentUser, $currentQuestion, $answerObj, $this);
        $response_data = array(
            'number' => $number + 1,
            'nextQuestionLink' => $nextQuestionLink,
            'answers' => $answers,
            'correctness' => $answerObj->getisTrue(),
            'nextQuestion' => $nextQuestionText,
            'toResultLink' => "",
        );
        $response_data['toResultLink'] = $drawer->findOutToResultLink($currentQuiz, $nextQuestionNumber, $slug, $this);
        return $this->json($response_data);
    }
}
