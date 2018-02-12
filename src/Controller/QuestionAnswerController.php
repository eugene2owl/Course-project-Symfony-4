<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionAnswerController extends Controller
{
    /**
     * @Route("/createset", name="set")
     * @return Response
     */
    public function index()
    {
        $question = new Question("How much do you want?");

        $answer = new Answer("One more!");

        $em = $this->getDoctrine()->getManager();
        $em->persist($question);
        $em->persist($answer);
        $em->flush();

        $question->addAnswer($answer);
        $em->persist($question);
        $em->persist($answer);
        $em->flush();

        return new Response(
            'Saved new answer with id: '.$answer->getId()
            .' and new question with id: '.$question->getId()
            .' answers are : '.var_dump($question)
        );
    }
}