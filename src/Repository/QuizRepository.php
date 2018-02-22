<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityRepository;

class QuizRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function findAllLike($text): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT p
        FROM App\Entity\Quiz p
        WHERE (p.quizname LIKE :text) OR 
        (p.firstNameLider LIKE :text) OR 
        (p.secondNameLider LIKE :text) OR 
        (p.thirdNameLider LIKE :text) OR
        (p.status LIKE :text) OR 
        (p.id LIKE :text) OR 
        (p.id LIKE :text)
        '
        )->setParameter('text', ("%".$text."%"));

        return $query->execute();
    }
}
