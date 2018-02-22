<?php

namespace App\Repository;

use App\Entity\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ResultRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Result::class);
    }

    public function findAllLike($text): array
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT p
        FROM App\Entity\Result p
        WHERE p.id LIKE :text'
        )->setParameter('text', ("%".$text."%"));

        return $query->execute();
    }
}
