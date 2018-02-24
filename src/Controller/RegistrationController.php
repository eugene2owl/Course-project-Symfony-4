<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->getUser() != null) {
            $toMainLink = $this->generateUrl('main');
            return $this->redirect($toMainLink);
        }
        session_reset();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()  && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $toMainLink = $this->generateUrl('main');
            return $this->redirect($toMainLink);
        }
        $formView = $form->createView();
        $toLoginLink = $this->generateUrl('login');
        return $this->render('Registration/register.html.twig', [
                'toLoginLink' => $toLoginLink,
                'form' => $formView
            ]
        );
    }
}
