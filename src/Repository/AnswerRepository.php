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

    public function findAllOrdLike($pattern, $field, $order): array
    {
        if ($field == 'name') {
            $field = 'text';
        }
        if ($field == 'username') {
            $field = '';
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM answer p';
        if ($pattern != '') {
            $sql .= ' WHERE p.text LIKE :pattern OR
            p.is_true LIKE :pattern OR
            p.question_id LIKE :pattern OR
            p.id LIKE :pattern';
        }
        if ($field != '') {
            $sql .= ' ORDER BY p.'.$field.' '.$order;
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute(['pattern' => '%'.$pattern.'%']);

        return $stmt->fetchAll();
    }
}
