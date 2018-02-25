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

    public function findAllOrdLike(string $pattern, string $fieldSort, string $order, array $fieldFilter): array
    {
        if ($fieldSort == 'name') {
            $fieldSort = 'text';
        }
        if ($fieldSort == 'username') {
            $fieldSort = '';
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM question p';
        if ($pattern != '') {
            if (in_array("username", $fieldFilter)) {
                array_splice($fieldFilter, array_search('username', $fieldFilter), 1);
            }
            if (count($fieldFilter) > 0) {
                $sql .= ' WHERE ';
                for ($number = 0; $number < count($fieldFilter); $number++) {
                    if ($fieldFilter[$number] == 'name') {
                        $number > 0 ? $sql .= ' OR p.text LIKE :pattern' : $sql .= 'p.text LIKE :pattern';
                    }
                    if ($fieldFilter[$number] == 'id') {
                        $number > 0 ? $sql .= ' OR p.id LIKE :pattern' : $sql .= 'p.id LIKE :pattern';
                    }
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
