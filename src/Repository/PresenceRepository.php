<?php

namespace App\Repository;

use App\Entity\Presence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Presence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Presence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Presence[]    findAll()
 * @method Presence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Presence::class);
    }

    // /**
    //  * @return Presence[] Returns an array of Presence objects
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
    public function findOneBySomeField($value): ?Presence
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function PresentQuery()
    {
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $query = $this->getEntityManager()
            ->createQuery("SELECT 
             e.id as id ,e.nom as nom , e.prenom as prenom ,
              e.societe as societe, e.poste as poste, e.matricule as matricule,e.code as code,e.t as t , 
             p.poste as idPoste, h.present as present , h.retard as retard , h.datePresence as dateP 
                from App:Employee e , App:Presence h , App:Poste p
                where h.datePresence = '$current'
                and h.idEmployee = e.id
                and e.idPoste = p.id
                group by e.id
                ");
        $res = $query->getResult();
        return $res;

    }

    public function rapport(){
        $currentDateS = new \DateTime('now');
        $currentDateE = new \DateTime('now');

        var_dump($currentDateS);
        $start= $currentDateS->setTime(24,00,00);
        $end = $currentDateE->setTime(0,0);

        $startS= $start->format('Y-m-d H:i:s');
        $endS= $end->format('Y-m-d H:i:s');
        $query = $this->getEntityManager()
            ->createQuery("SELECT u.id as id, u.code as code, u.t as t, u.matricule as matricule
            ,u.nom as nom , u.prenom as prenom, u.societe as societe,
            u.poste as pro , po.poste as poste , p.present as present, p.retard as retard , p.datePresence as date
            FROM App:Presence p , App:User u , App:Poste po
            WHERE p.idUser = u.id
            and u.idPoste= po.id
            and p.datePresence BETWEEN '$startS' AND '$endS'
            GROUP BY u.username");

        $res = $query->getResult();
        return $res;
    }
}
