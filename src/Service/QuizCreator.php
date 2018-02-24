<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuizCreator extends Controller
{
    public function createQuiz(Controller $controller)
    {
        $quiz = new Quiz();
        $quiz->setQuizname("Math");
        $quiz->setPlayersAmount(0);
        $quiz->setStatus(1);
        $quiz->setFirstNameLider("");
        $quiz->setSecondNameLider("");
        $quiz->setThirdNameLider("");
        $quiz->setBirthday(date('jS F Y h:i:s'));


        $question = new Question("2 + 3?");
        $question1 = new Question("2 x 2 = 4?");
        $question2 = new Question("3 x 3?");
        $question3 = new Question("7 x 0?");


        $answer1question1 = new Answer("5", 1);
        $answer2question1 = new Answer("6", 0);
        $answer3question1 = new Answer("7", 0);

        $answer1question2 = new Answer("Yes", 1);
        $answer2question2 = new Answer("No", 0);

        $answer1question3 = new Answer("9", 1);
        $answer2question3 = new Answer("2", 0);
        $answer3question3 = new Answer("4", 0);

        $answer1question4 = new Answer("0", 1);
        $answer2question4 = new Answer("3", 0);
        $answer3question4 = new Answer("5", 0);
        $answer4question4 = new Answer("4", 0);


        $question->setQuiz($quiz);
        $question1->setQuiz($quiz);
        $question2->setQuiz($quiz);
        $question3->setQuiz($quiz);

        $quiz->setQuestionList($question);
        $quiz->setQuestionList($question1);
        $quiz->setQuestionList($question2);
        $quiz->setQuestionList($question3);


        $question->setAnswerList($answer1question1);
        $question->setAnswerList($answer2question1);
        $question->setAnswerList($answer3question1);

        $question1->setAnswerList($answer1question2);
        $question1->setAnswerList($answer2question2);

        $question2->setAnswerList($answer1question3);
        $question2->setAnswerList($answer2question3);
        $question2->setAnswerList($answer3question3);

        $question3->setAnswerList($answer1question4);
        $question3->setAnswerList($answer2question4);
        $question3->setAnswerList($answer3question4);
        $question3->setAnswerList($answer4question4);

        $em = $controller->getDoctrine()->getManager();


        $em->persist($answer1question1);
        $em->persist($answer2question1);
        $em->persist($answer3question1);

        $em->persist($answer1question2);
        $em->persist($answer2question2);

        $em->persist($answer1question3);
        $em->persist($answer2question3);
        $em->persist($answer3question3);

        $em->persist($answer1question4);
        $em->persist($answer2question4);
        $em->persist($answer3question4);
        $em->persist($answer4question4);

        $em->persist($quiz);

        $em->persist($question);
        $em->persist($question1);
        $em->persist($question2);
        $em->persist($question3);
        //$em->flush();
    }
}