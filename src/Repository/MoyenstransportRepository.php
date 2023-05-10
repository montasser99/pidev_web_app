<?php

namespace App\Repository;

use App\Entity\Moyenstransport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Moyenstransport>
 *
 * @method Moyenstransport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Moyenstransport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Moyenstransport[]    findAll()
 * @method Moyenstransport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoyenstransportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Moyenstransport::class);
    }

    public function save(Moyenstransport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Moyenstransport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Moyenstransport[] Returns an array of Moyenstransport objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Moyenstransport
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function getIdMoy($type)
{
    return $this->createQueryBuilder('m')
    ->select('m.idmoy')
    ->where('m.type = :type')
    ->setParameter('type', $type)
    ->getQuery()
    ->getResult();
}

public function filterTransportByEnter($searchQuery)
{
     $query = $this->createQueryBuilder('m')
    ->where('m.type LIKE :searchQuery OR m.matricule LIKE :searchQuery OR m.capacite LIKE :searchQuery OR m.nummoy LIKE :searchQuery ')
    ->setParameter('searchQuery', '%' . $searchQuery . '%')    
    ->getQuery();

    return $query->getResult();

}



}
