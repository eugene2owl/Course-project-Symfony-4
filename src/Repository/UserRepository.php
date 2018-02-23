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

    public function findAllOrdLike($pattern, $field): array
    {
        if ($field == 'name') {
            $field = 'firstname';
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM user p';
        if ($pattern != '') {
            $sql .= ' WHERE p.username LIKE :pattern OR
            p.id LIKE :pattern OR
            p.city LIKE :pattern OR
            p.email LIKE :pattern OR
            p.roles LIKE :pattern OR
            p.firstname LIKE :pattern OR
            p.secondname LIKE :pattern OR
            p.thirdname LIKE :pattern';
        }
        if ($field != '') {
            $sql .= ' ORDER BY p.'.$field.' ASC';
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute(['pattern' => '%'.$pattern.'%']);

        return $stmt->fetchAll();
    }
}
