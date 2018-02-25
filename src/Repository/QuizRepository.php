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

    public function findAllOrdLike(string $pattern, string $fieldSort, string $order, array $fieldFilter): array
    {
        if ($fieldSort == 'name') {
            $fieldSort = 'quizname';
        }
        if ($fieldSort == 'username') {
            $fieldSort = 'first_name_Lider';
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM quiz p';
        if ($pattern != '') {
            $sql .= ' WHERE ';
            for ($number = 0; $number < count($fieldFilter); $number++) {
                if ($fieldFilter[$number] == 'username') {
                    $number > 0 ? $sql .= ' OR p.first_name_Lider LIKE :pattern' : $sql .= 'p.first_name_Lider LIKE :pattern';
                }
                if ($fieldFilter[$number] == 'name') {
                    $number > 0 ? $sql .= ' OR p.quizname LIKE :pattern' : $sql .= 'p.quizname LIKE :pattern';
                }
                if ($fieldFilter[$number] == 'id') {
                    $number > 0 ? $sql .= ' OR p.id LIKE :pattern' : $sql .= 'p.id LIKE :pattern';
                }
            }
        }
        if ($fieldSort != '') {
            $sql .= ' ORDER BY p.'.$fieldSort.' '.$order;
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute(['pattern' => '%'.$pattern.'%']);

        return $stmt->fetchAll();
    }
}
