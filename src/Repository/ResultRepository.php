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

    public function findAllOrdLike($pattern, $field, $order): array
    {
        if ($field == 'name') {
            $field = '';
        }
        if ($field == 'username') {
            $field = '';
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM answer p';
        if ($pattern != '') {
            $sql .= ' WHERE p.id LIKE :pattern';
        }
        if ($field != '') {
            $sql .= ' ORDER BY p.'.$field.' '.$order;
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute(['pattern' => '%'.$pattern.'%']);

        return $stmt->fetchAll();
    }
}
