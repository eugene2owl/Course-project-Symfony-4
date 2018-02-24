<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PlayersRightsCalculator;

class MainController extends Controller
{
    /**
     * @Route("/main", name="main")
     */
    public function mainFunction(PlayersRightsCalculator $rightsCalculator)
    {
        $quizzes = $rightsCalculator->findAllQuizzes($this);
        $enabledArray = $rightsCalculator->findPassedQuizzes($quizzes, $this);
        $toQuizLink = 'http://symfony4.loc/main/quizzes/';
        $toQuizResultLink = 'http://symfony4.loc/main/quizResults/';
        return $this->render('security/main.html.twig', array(
            'quizList' => $quizzes,
            'enabledArray' => $enabledArray,
            'toQuizLink' => $toQuizLink,
            'toQuizResultLink' => $toQuizResultLink,
        ));
    }
}
