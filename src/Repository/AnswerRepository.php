<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function findAllLike($text): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT p
        FROM App\Entity\Answer p
        WHERE (p.text LIKE :text) OR 
        (p.isTrue LIKE :text) OR 
        (p.id LIKE :text)
        '
        )->setParameter('text', ("%".$text."%"));

        return $query->execute();
    }
}
