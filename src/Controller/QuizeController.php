<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuizType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuizRepository;
use Symfony\Component\HttpFoundation\Response;

class QuizeController extends Controller
{
    /**
     * @Route("/main/quizzes/{slug}",
     *     name="quizStart",
     *     )
     */
    public function quizStart(Request $request, $slug)
    {
        /*$repository = $this->getDoctrine()->getRepository(Quiz::class);
        $arr = $repository->findBy(['quizname' => 'Presidents']);

        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->questionNumber++;
        }

        return $this->render('Quizzes/quiz.html.twig', array(
            'quiz' => $arr,
            'form' => $form->createView(),
            'questionNumber' => $this->questionNumber,
        ));*/
        $currentQuestionNumber = 1;
        return new RedirectResponse($slug."/".$currentQuestionNumber);
    }

    /**
     * @Route("/main/quizzes/{slug}/{number}",
     *     name="currentQuestionPage",
     *     )
     */
    public function ShowCurrentQuestionPage(Request $request, $slug,int $number)
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);
        $arr = $repository->findBy(['quizname' => $slug]);

        $currentQuiz = $arr[0];
        $nextQuestionLink = $this->generateUrl('currentQuestionPage', ['slug'=>$slug, 'number' => $number+1]);
        if (count($currentQuiz->getQuestionList()) > $number - 1) {
            return $this->render('Quizzes/quiz.html.twig', array(
                'currentQuestionNumber' => $number,
                'quiz' => $currentQuiz,
                'nextQuestionLink' => $nextQuestionLink,
            ));
        } else {
            $toResultsLink = $this->generateUrl('QuizResults', ['slug'=>$slug]);
            return new RedirectResponse($toResultsLink);
        }
    }

    /**
     * @Route("/main/quizResults/{slug}",
     *     name="QuizResults",
     *     )
     */
    public function ShowQuizResults(Request $request, $slug)
    {
        return $this->render('Quizzes/quizResult.html.twig', array(
            'quizName' => $slug,
        ));
    }
}