<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllOrdLike(string $pattern, string $fieldSort, string $order, array $fieldFilter): array
    {
        if ($fieldSort == 'name') {
            $fieldSort = 'firstname';
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM user p';
        if ($pattern != '') {
            if (count($fieldFilter) > 0) {
                $sql .= ' WHERE ';
                for ($number = 0; $number < count($fieldFilter); $number++) {
                    if ($fieldFilter[$number] == 'username') {
                        $number > 0 ? $sql .= ' OR p.username LIKE :pattern' : $sql .= 'p.username LIKE :pattern';
                    }
                    if ($fieldFilter[$number] == 'name') {
                        $number > 0 ? $sql .= ' OR p.firstname LIKE :pattern' : $sql .= 'p.firstname LIKE :pattern';
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
