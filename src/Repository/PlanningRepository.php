<?php

namespace App\Repository;

use App\Entity\Circuit;
use App\Entity\Moyenstransport;
use App\Entity\Planning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planning>
 *
 * @method Planning|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planning|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planning[]    findAll()
 * @method Planning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planning::class);
    }

    public function save(Planning $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Planning $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
   

//    /**
//     * @return Planning[] Returns an array of Planning objects
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

//    public function findOneBySomeField($value): ?Planning
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

// public function getRelatedCircuit(int $idcir)
// {
//     return $this->createQueryBuilder('p')
//         ->join('p.idcir', 'c')
//         ->select('c.nomc')
//         ->where('c.idcircuit = :idcir')
//         ->setParameter('idcir', $idcir)
//         ->getQuery()
//         ->getResult();
// }

// //Requette recherche avec circuit
// public function FilterPlanningByCircuit(string $filter)
// {
//     return $this->createQueryBuilder('p')
//         ->leftJoin('p.idcir', 'c')
//         ->andWhere("c.departc LIKE :filter OR c.arriveec 
//         LIKE :filter OR CONCAT(c.departc ,' ', c.arriveec ) LIKE :filter OR CONCAT(c.arriveec,' ',c.departc) LIKE :filter")
//         ->setParameter('filter', '%'.$filter.'%')
//         ->getQuery()
//         ->getResult();
// }

// //Requette recherche avec moyenne de transport
// public function FilterPlanningByMoyen(string $filter)
// {
//     return $this->createQueryBuilder('p')
//         ->leftJoin('p.idmoy', 'm')
//         ->andWhere("m.type LIKE :filter OR m.nummoy 
//         LIKE :filter OR CONCAT(m.type ,' ', m.nummoy ) LIKE :filter OR CONCAT(m.nummoy,' ',m.type) LIKE :filter")
//         ->setParameter('filter', '%'.$filter.'%')
//         ->getQuery()
//         ->getResult();
// }

// //Requette recherche avec date de depart
// public function FilterPlanningByDateD(string $filter)
// {
//     return $this->createQueryBuilder('p')
//         ->andWhere("p.dated LIKE :filter OR p.datea 
//         LIKE :filter OR CONCAT(p.dated ,' ', p.datea) LIKE :filter")
//         ->setParameter('filter', '%'.$filter.'%')
//         ->getQuery()
//         ->getResult();
// }

// public function search($searchQuery)
// {
//     $query = $this->createQueryBuilder('p')
//         ->leftJoin('p.idcir', 'c')
//         ->leftJoin('p.idmoy', 'm')
//         ->where('c.departc LIKE :searchQuery OR c.arriveec LIKE :searchQuery OR m.type LIKE :searchQuery')
//         ->setParameter('searchQuery', '%' . $searchQuery . '%')
//         ->orderBy('p.dated', 'DESC')
//         ->getQuery();

//     return $query->getResult();
// }

public function filterPlanningByEnter($searchQuery)
{
    $query = $this->createQueryBuilder('p')
        ->leftJoin('p.idcir', 'c')
        ->leftJoin('p.idmoy', 'm')
        ->where('c.departc LIKE :searchQuery OR c.arriveec LIKE :searchQuery OR m.type LIKE :searchQuery
        OR m.nummoy LIKE :searchQuery OR p.dated LIKE :searchQuery OR p.datea LIKE :searchQuery OR p.nbplace LIKE :searchQuery 
        OR p.prix LIKE :searchQuery')
        ->setParameter('searchQuery', '%' . $searchQuery . '%')
        ->orderBy('p.dated', 'DESC')
        ->getQuery();

    return $query->getResult();
}

public function addOneToReservation($datedep,$idc,$idm): void
{
    $qb = $this->createQueryBuilder('p')
        ->update(Planning::class, 'p')
        ->set('p.nbplace', 'p.nbplace - 1')
        ->where('p.dated = :datedep')
        ->andWhere('p.idcir = :idc')
        ->andWhere('p.idmoy = :idm')
        ->setParameter('datedep', $datedep)
        ->setParameter('idc', $idc)
        ->setParameter('idm', $idm);

    $qb->getQuery()->execute();
}

public function UpdateNbplaceToCapacite($datedep, $idc, $idm , $capacite): void
{
    $qb = $this->createQueryBuilder('p')
        ->update(Planning::class, 'p')
        ->set('p.nbplace', ':cap')
        ->where('p.dated = :datedep')
        ->andWhere('p.idcir = :idc')
        ->andWhere('p.idmoy = :idm')
        ->setParameter('datedep', $datedep)
        ->setParameter('idc', $idc)
        ->setParameter('idm', $idm)
        ->setParameter('cap', $capacite)
    ;

    $qb->getQuery()->execute();
}

//get idMoy , type
public function getDistinctTransports()
{
    return $this->createQueryBuilder('p')
        ->innerJoin('App\Entity\Moyenstransport', 'm', 'WITH', 'p.idmoy = m.idmoy')
        ->select('DISTINCT m.idmoy as idmoy, m.type as type')
        ->getQuery()
        ->getResult();
}

//get idCir , circuitA, circuitD
public function getDistinctCircuits()
{
    return $this->createQueryBuilder('p')
        ->innerJoin('App\Entity\Circuit', 'c', 'WITH', 'p.idcir = c.idcircuit')
        ->select('DISTINCT c.idcircuit as idcir, c.departc ,c.arriveec')
        ->getQuery()
        ->getResult();
}



//recherche avancée
public function getPlanningAvanceR($idcir,$idmoy,$intervalle1,$intervalle2){
    return $this->createQueryBuilder('p')
    ->where('p.idcir = :idcir AND p.idmoy = :idmoy AND p.dated between  :intervalle1 AND :intervalle2 ')
    ->setParameter('idcir', $idcir)
    ->setParameter('idmoy', $idmoy)
    ->setParameter('intervalle1', $intervalle1)
    ->setParameter('intervalle2', $intervalle2)
    ->getQuery()
    ->getResult();
}

//recherche sur table planning : 
public function RechercheReservPlanning($idC , $idM , $dateNow)
{
    return $this->createQueryBuilder('p')
    ->where('p.idcir = :idC AND p.idmoy = :idM AND p.dated > :dateNow')
    ->setParameter('idC', $idC)
    ->setParameter('idM', $idM)
    ->setParameter('dateNow', $dateNow)
    ->getQuery()
    ->getResult();

}
public function getPlanning($dated,$idcir,$idmoy)
{
    return $this->createQueryBuilder('p')
    ->where('p.idcir = :idcir AND p.idmoy = :idmoy AND p.dated = :dated')
    ->setParameter('dated', $dated)
    ->setParameter('idcir', $idcir)
    ->setParameter('idmoy', $idmoy)
    ->getQuery()
    ->getResult();
}

}