<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EntitiesPuller extends Controller
{
    public function getEntitiesArray(string $slug, string $sortByField, string $pattern, string $order, Controller $controller)
    {
        switch ($slug) {
            case 'quiz':
                $entities = $controller->getDoctrine()->getRepository(Quiz::class)->findAllOrdLike($pattern, $sortByField, $order);
                break;
            case 'question':
                $entities = $controller->getDoctrine()->getRepository(Question::class)->findAllOrdLike($pattern, $sortByField, $order);
                break;
            case 'answer':
                $entities = $controller->getDoctrine()->getRepository(Answer::class)->findAllOrdLike($pattern, $sortByField, $order);
                break;
            case 'result':
                $entities = $controller->getDoctrine()->getRepository(Result::class)->findAllOrdLike($pattern, $sortByField, $order);
                break;
            case 'user':
                $entities = $controller->getDoctrine()->getRepository(User::class)->findAllOrdLike($pattern, $sortByField, $order);
                break;
            default:
                $entities = $controller->getDoctrine()->getRepository(Quiz::class)->findAll();
        }
        return $entities;
    }
}