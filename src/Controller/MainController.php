<?php

namespace App\Controller;

use App\Entity\Quiz;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuizRepository;

class MainController extends Controller
{
    /**
     * @Route("/main", name="main")
     */
    public function mainFunction()
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);
        $arr = $repository->findAll();
        $enabledArray = $this->findPassedQuizzes($arr);
        return $this->render('security/main.html.twig', array(
            'quizList' => $arr,
            'enabledArray' => $enabledArray,
        ));
    }

    private function findPassedQuizzes(array $quizArray): array
    {
        $enabledArray = [];
        for ($currentQuiz = 0; $currentQuiz < count($quizArray); $currentQuiz++) {
            $isPassed = false;
            $amountOfPassedTasks = 0;
            foreach ($quizArray[$currentQuiz]->MYgetResultList() as $quizResult) {
                if ($quizResult->getUser() == $this->getUser()) {
                    $amountOfPassedTasks++;
                }
            }
            if ($amountOfPassedTasks == count($quizArray[$currentQuiz]->getQuestionList())) {
                $isPassed = true;
            }
            $isPassed ? array_push($enabledArray, true) : array_push($enabledArray, false);
        }
        return $enabledArray;
    }
}