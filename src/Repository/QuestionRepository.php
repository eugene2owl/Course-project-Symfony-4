<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findAllLike($text): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT p
        FROM App\Entity\Question p
        WHERE p.text LIKE :text OR 
        p.id LIKE :text'
        )->setParameter('text', ("%".$text."%"));

        return $query->execute();
    }
}
