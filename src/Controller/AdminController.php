<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\EntitiesPuller;

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
    public function drawTable(Request $request, string $slug, EntitiesPuller $puller)
    {
        $dataFromAjax = $request->get('data');

        $sortField = $dataFromAjax[0]['sortbyfield'];
        $sortOrder = $dataFromAjax[1]['order'];
        $pattern = $dataFromAjax[2]['pattern'];
        $filterField = $dataFromAjax[3]['filterableColumns'];

        $entities = $puller->getEntitiesArray($slug, $sortField, $pattern, $sortOrder, $filterField, $this);
        $table_data = [
            'entities' => $entities,
        ];
        return $this->json($table_data);
    }
}
