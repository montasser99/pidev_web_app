<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\DateFormat;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);

        // Register custom functions in Doctrine configuration
        $config = new Configuration();
        $config->addCustomStringFunction('DATE_FORMAT', DateFormat::class);
        $entityManager = $this->getEntityManager();
        $entityManager->getConfiguration()->addCustomStringFunction('DATE_FORMAT', DateFormat::class);
    }

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filterReservationByEnter($searchQuery)
    {
        $query = $this->createQueryBuilder('r')
            ->where('r.idnum LIKE :searchQuery OR r.dater LIKE :searchQuery OR r.heuredep LIKE :searchQuery OR r.heurearr LIKE :searchQuery
            OR r.type LIKE :searchQuery OR r.cin LIKE :searchQuery OR r.prix LIKE :searchQuery OR r.numerot LIKE :searchQuery')
            ->setParameter('searchQuery', '%' . $searchQuery . '%')
            ->orderBy('r.idnum', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    public function GetReservation($dateNow)
    {
        $startDate = $dateNow . ' 00:00:00';
        $endDate = $dateNow . ' 23:59:59';
    
        $qb = $this->createQueryBuilder('r')
            ->select('r.datereservation, r.idnum, r.type, r.numerot, SUM(r.prix) as sum')
            ->where('r.datereservation BETWEEN :start_date AND :end_date')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate)
            ->groupBy('r.type')
            ->addGroupBy('r.numerot')
            ->getQuery();
    
        return $qb->getResult();
    }

    public function getReservationDejour($cin, $dateObj)
    {
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder->where('r.cin = :cin')
        ->andWhere("DATE_FORMAT(r.datereservation, '%Y-%m-%d') = :dateObj")

            ->setParameter('cin', $cin)
            ->setParameter('dateObj', $dateObj);
    
        $query = $queryBuilder->getQuery();
    
        return $query->getResult();
    }
    public function getReservationHistorique($cin)
    {
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder->where('r.cin = :cin')
        ->setParameter('cin', $cin);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

}
