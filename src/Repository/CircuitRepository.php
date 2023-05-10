<?php

namespace App\Repository;

use App\Entity\Circuit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Circuit>
 *
 * @method Circuit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Circuit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Circuit[]    findAll()
 * @method Circuit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CircuitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Circuit::class);
    }

    public function save(Circuit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Circuit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Circuit[] Returns an array of Circuit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Circuit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function search($query)
    {
        return $this->createQueryBuilder('c')
           ->where('c.nomc LIKE :query')
           ->orWhere('c.departc LIKE :query')
           ->orWhere('c.arriveec LIKE :query')
           ->setParameter('query', '%' . $query . '%')
           ->orderBy('c.nomc', 'ASC')
           ->getQuery()
           ->getResult();
   }


public function getRelatedCircuit(int $idcir)
{
    return $this->createQueryBuilder('c')
        ->select('c.nomc')
        ->where('c.idcircuit = :idcir')
        ->setParameter('idcir', $idcir)
        ->getQuery()
        ->getResult();
}

public function getIdCircuit($cirD,$cirA)
{
    return $this->createQueryBuilder('c')
    ->select('c.idcircuit')
    ->where('c.departc = :idciD AND c.arriveec = :idcirA ')
    ->setParameter('idciD', $cirD)
    ->setParameter('idcirA', $cirA)
    ->getQuery()
    ->getResult();
}

}
