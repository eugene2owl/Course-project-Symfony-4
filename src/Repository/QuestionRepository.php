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

    public function findAllOrdLike($pattern, $field, $order): array
    {
        if ($field == 'name') {
            $field = 'text';
        }
        if ($field == 'username') {
            $field = '';
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM question p';
        if ($pattern != '') {
            $sql .= ' WHERE p.text LIKE :pattern OR
            p.quiz_id LIKE :pattern OR
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
