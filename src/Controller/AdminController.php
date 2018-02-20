<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $toQuizzesTableLink = $this->generateUrl('drawTable', ['slug'=>'quiz']);
        $toQuestionTableLink = $this->generateUrl('drawTable', ['slug'=>'question']);
        $toAnswerTableLink = $this->generateUrl('drawTable', ['slug'=>'answer']);
        $toResultTableLink = $this->generateUrl('drawTable', ['slug'=>'result']);
        $toUserTableLink = $this->generateUrl('drawTable', ['slug'=>'user']);
        return $this->render('adminPage.html.twig', [
            'toQuizzesTableLink' => $toQuizzesTableLink,
            'toQuestionTableLink' => $toQuestionTableLink,
            'toAnswerTableLink' => $toAnswerTableLink,
            'toResultTableLink' => $toResultTableLink,
            'toUserTableLink' => $toUserTableLink,
        ]);
    }

    /**
     * @Route("/admin/{slug}", name="drawTable")
     */
    public function drawTable(string $slug)
    {
        $entities = null;
        switch ($slug) {
            case 'quiz' : $entities = $this->getEntities('quiz');
            break;
            case 'question' : $entities = $this->getEntities('question');
            break;
            case 'answer' : $entities = $this->getEntities('answer');
            break;
            case 'result' : $entities = $this->getEntities('result');
            break;
            case 'user' : $entities = $this->getEntities('user');
            break;
            default: $entities = $this->getEntities('quiz');
        }
        $table_data = array(
            'entities' => $entities,
        );
        return $this->json($table_data);
    }

    private function getEntities(string $entityName)
    {
        $entities = null;
        switch ($entityName) {
            case 'quiz' : $entities = $this->getDoctrine()->getRepository(Quiz::class)->findAll();
            break;
            case 'question' : $entities = $this->getDoctrine()->getRepository(Question::class)->findAll();
            break;
            case 'answer' : $entities = $this->getDoctrine()->getRepository(Answer::class)->findAll();
            break;
            case 'result' : $entities = $this->getDoctrine()->getRepository(Result::class)->findAll();
            break;
            case 'user' : $entities = $this->getDoctrine()->getRepository(User::class)->findAll();
            break;
        }
        return $entities;
    }
}