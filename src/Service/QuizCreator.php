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
        $quiz->setQuizname("Animals");
        $quiz->setPlayersAmount(0);
        $quiz->setStatus(1);
        $quiz->setFirstNameLider("");
        $quiz->setSecondNameLider("");
        $quiz->setThirdNameLider("");
        $quiz->setBirthday(date('jS F Y h:i:s'));


        $question = new Question("Elephant is?");
        $question1 = new Question("Lion is a tiger?");
        $question2 = new Question("How much paws does fish have?");
        $question3 = new Question("Who is a king of animals?");


        $answer1question1 = new Answer("grey", 1);
        $answer2question1 = new Answer("red", 0);
        $answer3question1 = new Answer("blue", 0);

        $answer1question2 = new Answer("No", 1);
        $answer2question2 = new Answer("Yes", 0);

        $answer1question3 = new Answer("0", 1);
        $answer2question3 = new Answer("2", 0);
        $answer3question3 = new Answer("4", 0);

        $answer1question4 = new Answer("Elephant", 0);
        $answer2question4 = new Answer("Lion", 1);
        $answer3question4 = new Answer("Cat", 0);
        $answer4question4 = new Answer("Puppy", 0);


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