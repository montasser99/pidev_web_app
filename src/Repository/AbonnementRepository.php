<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnement>
 *
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    public function save(Abonnement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Abonnement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



public function getNbBus()
{
    return $this->createQueryBuilder('a')
        ->select('COUNT(a.id)')
        ->where('a.moyenTransportA = :transport')
        ->setParameter('transport', 'Bus')
        ->getQuery()
        ->getSingleScalarResult();
}

public function getNbMetro()
{
    return $this->createQueryBuilder('a')
        ->select('COUNT(a.id)')
        ->where('a.moyenTransportA = :transport')
        ->setParameter('transport', 'Metro')
        ->getQuery()
        ->getSingleScalarResult();
}

public function getNbTrain()
{
    return $this->createQueryBuilder('a')
        ->select('COUNT(a.id)')
        ->where('a.moyenTransportA = :transport')
        ->setParameter('transport', 'Train')
        ->getQuery()
        ->getSingleScalarResult();
}

public function getNbM()
{
    return $this->createQueryBuilder('a')
        ->select('COUNT(a.id)')
        ->leftJoin('a.plan', 'p')
        ->where('p.id = :planId')
        ->setParameter('planId', '1')
        ->getQuery()
        ->getSingleScalarResult();
}
public function getNbS()
{
    return $this->createQueryBuilder('a')
        ->select('COUNT(a.id)')
        ->leftJoin('a.plan', 'p')
        ->where('p.id = :planId')
        ->setParameter('planId', '2')
        ->getQuery()
        ->getSingleScalarResult();
}
public function getNbA()
{
    return $this->createQueryBuilder('a')
        ->select('COUNT(a.id)')
        ->leftJoin('a.plan', 'p')
        ->where('p.id = :planId')
        ->setParameter('planId', '3')
        ->getQuery()
        ->getSingleScalarResult();
}


}
