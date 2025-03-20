<?php

namespace App\Repository;

use App\Entity\Panier;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }



// public function findByUserAndConfirmed(Users $user, bool $confirmed): array
// {
//     return $this->createQueryBuilder('p')
//         ->innerJoin('p.commands', 'c')
//         ->andWhere('c.user = :user')
//         ->andWhere('p.confirmed = :confirmed')
//         ->setParameter('user', $user)
//         ->setParameter('confirmed', $confirmed)
//         ->getQuery()
//         ->getResult();
// }


//    /**
//     * @return Panier[] Returns an array of Panier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Panier
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
