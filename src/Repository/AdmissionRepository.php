<?php

namespace App\Repository;

use App\Entity\Admission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Admission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Admission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Admission[]    findAll()
 * @method Admission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdmissionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Admission::class);
    }

    // /**
    //  * @return Admission[] Returns an array of Admission objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Admission
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
