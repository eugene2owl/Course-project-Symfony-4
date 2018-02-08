<?php

namespace App\Controller;

use App\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        $question = new Question('How are you?');
        $answer = new Answer('Nice');
        $answer2 = new Answer('Great');

        $question->addAnswer($answer);
        $question->addAnswer($answer2);

        $doctrine = $this->getDoctrine();

        $doctrine->getManager()->persist($answer);
        $doctrine->getManager()->persist($question);
        $doctrine->getManager()->persist($answer2);


        $doctrine->getManager()->flush();

        return new Response( '<html><body>Admin</body></html>');
    }
}