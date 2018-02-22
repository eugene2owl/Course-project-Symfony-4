<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class QuizQuestionsController extends Controller
{
    /**
     * @Route("/createQuizQuestionTie", name="createQuizQuestionTie")
     */
    public function index()
    {
        $quiz = new Quiz();
        $quiz->setQuizname("Colors");
        $quiz->setPlayersAmount(0);
        $quiz->setStatus(1);
        $quiz->setFirstNameLider("Test");
        $quiz->setSecondNameLider("Test2");
        $quiz->setThirdNameLider("Test3");


        $question = new Question("What is a color of sunset?");
        $question1 = new Question("What is a color o chameleon?");
        $question2 = new Question("What are the colors of Canada flag?");


        $answer1question1 = new Answer("Blue", 0);
        $answer2question1 = new Answer("White", 0);
        $answer3question1 = new Answer("Red", 1);

        $answer1question2 = new Answer("Green", 0);
        $answer2question2 = new Answer("Random", 0);
        $answer3question2 = new Answer("Color of it's environment", 1);

        $answer1question3 = new Answer("Red and white", 0);
        $answer2question3 = new Answer("Red", 0);
        $answer3question3 = new Answer("White and black", 1);


        $question->setQuiz($quiz);
        $question1->setQuiz($quiz);
        $question2->setQuiz($quiz);

        $quiz->setQuestionList($question);
        $quiz->setQuestionList($question1);
        $quiz->setQuestionList($question2);


        $question->setAnswerList($answer1question1);
        $question->setAnswerList($answer2question1);
        $question->setAnswerList($answer3question1);
        $question1->setAnswerList($answer1question2);
        $question1->setAnswerList($answer2question2);
        $question1->setAnswerList($answer3question2);
        $question2->setAnswerList($answer1question3);
        $question2->setAnswerList($answer2question3);
        $question2->setAnswerList($answer3question3);


        $em = $this->getDoctrine()->getManager();


        $em->persist($answer1question1);
        $em->persist($answer2question1);
        $em->persist($answer3question1);
        $em->persist($answer1question2);
        $em->persist($answer2question2);
        $em->persist($answer3question2);
        $em->persist($answer1question3);
        $em->persist($answer2question3);
        $em->persist($answer3question3);

        $em->persist($quiz);

        $em->persist($question);
        $em->persist($question1);
        $em->persist($question2);
        //$em->flush();

        return new Response(
            'Saved new question with id: '.$question->getId()
            .' and new quiz with id: '.$quiz->getId()
        );
    }
}
