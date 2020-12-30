<?php

namespace App\Repository;

use App\Entity\Conge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conge[]    findAll()
 * @method Conge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conge::class);
    }

    // /**
    //  * @return Conge[] Returns an array of Conge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conge
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function congeByUser($id){

        $query= $this->getEntityManager()
            ->createQuery("
            SELECT c.id as id, e.username as prenom , c.nbJours as nbConge , c.etat as etat, 
            c.dateDebut as dateDebut , c.dateFin as dateFin
            FROM App:Conge c , App:User e
            WHERE c.idUser=e.id
            AND e.id = $id
            ");
        $res = $query->getResult();
        return $res;
    }
    public function congeList(){

        $query= $this->getEntityManager()
            ->createQuery("
            SELECT c.id as id, e.username as prenom , c.nbJours as nbConge , c.etat as etat, 
            c.dateDebut as dateDebut , c.dateFin as dateFin
            FROM App:Conge c , App:User e
            WHERE c.idUser=e.id

            ");
        $res = $query->getResult();
        return $res;
    }

    public function congeValideQuery(){
        $query= $this->getEntityManager()
            ->createQuery("
            SELECT c
            FROM App:Conge c 
            WHERE c.etat= 'Validé'

            ");
        $res = $query->getResult();
        return $res;
    }
}
