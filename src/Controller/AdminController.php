<?php

declare(strict_types=1);

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
    public function drawTable(Request $request, string $slug)
    {
        $entities = null;

        $dataFromAjax = $request->get('data');

        $dataFromAjaxSort = $dataFromAjax[0]['sortbyfield'];
        $dataFromAjaxPattern = $dataFromAjax[1]['pattern'];

        $entities = $this->getEntitiesArray($slug, $dataFromAjaxSort, $dataFromAjaxPattern);

        $table_data = array(
            'entities' => $entities,
        );
        return $this->json($table_data);
    }

    private function getEntitiesArray(string $slug, string $sortByField, string $pattern)
    {
        switch ($slug) {
            case 'quiz':
                $entities = $this->getDoctrine()->getRepository(Quiz::class)->findAllOrdLike($pattern, $sortByField);
                break;
            case 'question':
                $entities = $this->getDoctrine()->getRepository(Question::class)->findAllOrdLike($pattern, $sortByField);
                break;
            case 'answer':
                $entities = $this->getDoctrine()->getRepository(Answer::class)->findAllOrdLike($pattern, $sortByField);
                break;
            case 'result':
                $entities = $this->getDoctrine()->getRepository(Result::class)->findAllOrdLike($pattern, $sortByField);
                break;
            case 'user':
                $entities = $this->getDoctrine()->getRepository(User::class)->findAllOrdLike($pattern, $sortByField);
                break;
            default:
                $entities = $this->getDoctrine()->getRepository(Quiz::class)->findAll();
        }
        /*if ($sortByField != "" && count($entities) != 0) {
            $entities = $this->sortEntities($entities, $sortByField);
        }*/
        return $entities;
    }

    private function sortEntities(array $entitiesArray, string $field): array
    {
        switch ($field) {
            case 'name':
                switch (get_class($entitiesArray[0])) {
                    case Quiz::class:
                        usort($entitiesArray, function ($a, $b) {
                            return strcmp($a->getQuizname(), $b->getQuizname());
                        });
                        break;
                    case Result::class:
                        usort($entitiesArray, function ($a, $b) {
                            return strcmp($a->getQuiz()->getQuizname(), $b->getQuiz()->getQuizname());
                        });
                        break;
                    case Question::class:
                    case Answer::class:
                        usort($entitiesArray, function ($a, $b) {
                            return strcmp($a->getText(), $b->getText());
                        });
                        break;
                    case User::class:
                        usort($entitiesArray, function ($a, $b) {
                            return strcmp($a->getUsername(), $b->getUsername());
                        });
                        break;
                }
                break;
            case 'username':
                switch (get_class($entitiesArray[0])) {
                    case Result::class:
                        usort($entitiesArray, function ($a, $b) {
                            return strcmp($a->getUser()->getUsername(), $b->getUser()->getUsername());
                        });
                        break;
                    case Quiz::class:
                        usort($entitiesArray, function ($a, $b) {
                            return strcmp($a->getFirstnameLider(), $b->getFirstnameLider());
                        });
                        break;
                    case User::class:
                        usort($entitiesArray, function ($a, $b) {
                            return strcmp($a->getUsername(), $b->getUsername());
                        });
                        break;
                }
        }
        return $entitiesArray;
    }
}
