<?php

namespace App\Repository;

use App\Entity\Perse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Perse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Perse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Perse[]    findAll()
 * @method Perse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Perse::class);
    }

    // /**
    //  * @return Perse[] Returns an array of Perse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Perse
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function perseGroupedByUser(){

        return $this->getEntityManager()
            ->createQuery("SELECT COUNT(perse.id) as total, user.username as username, perse.datePerse as date 
              FROM App:Perse perse ,App:User user
              where perse.idEmployee = user.id
                GROUP BY perse.idEmployee")
            ->getResult();
    }
    public function TotalPersePerDay(){
        $currentDateS = new \DateTime('now');
        $currentDateE = new \DateTime('now');

        //var_dump($currentDateS);
        $start= $currentDateS->setTime(24,00,00);
        $end = $currentDateE->setTime(0,0);

        $startS= $start->format('Y-m-d H:i:s');
        $endS= $end->format('Y-m-d H:i:s');
        //var_dump($endS);
        //var_dump($startS);

        return $this->getEntityManager()
            ->createQuery("SELECT COUNT(perse.id) as total, perse.datePerse as date 
              FROM App:Perse perse 
              WHERE perse.datePerse BETWEEN '$endS' and '$startS'
               ")
            ->getResult();
    }
    public function  listByUser($id){
        return $this->getEntityManager()
            ->createQuery("SELECT perse.id as id ,perse.note as note, user.username as username, perse.numPerse as numPerse, 
            perse.datePerse as datePerse
              FROM App:Perse perse ,App:User user
              WHERE perse.idEmployee = $id
              AND perse.idEmployee = user.id
               ")
            ->getResult();

    }



    public function  listAllUsers(){
        return $this->getEntityManager()
            ->createQuery("SELECT perse.id as id ,perse.note as note, user.username as username, perse.numPerse as numPerse, 
            perse.datePerse as datePerse
              FROM App:Perse perse ,App:User user
              WHERE perse.idEmployee = user.id
               ")
            ->getResult();

    }
}
