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

    public function findAllOrdLike($pattern, $field): array
    {
        if ($field == 'name') {
            $field = 'quizname';
        }
        if ($field == 'username') {
            $field = 'first_name_Lider';
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM quiz p';
        if ($pattern != '') {
            $sql .= ' WHERE p.quizname LIKE :pattern OR
            p.players_amount LIKE :pattern OR
            p.status LIKE :pattern OR
            p.birthday LIKE :pattern OR
            p.first_name_Lider LIKE :pattern OR
            p.second_name_Lider LIKE :pattern OR
            p.third_name_Lider LIKE :pattern OR
            p.id LIKE :pattern';
        }
        if ($field != '') {
            $sql .= ' ORDER BY p.'.$field.' ASC';
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute(['pattern' => '%'.$pattern.'%']);

        return $stmt->fetchAll();
    }
}
