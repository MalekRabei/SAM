<?php

namespace App\Repository;

use App\Entity\Calcul;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calcul|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calcul|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calcul[]    findAll()
 * @method Calcul[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalculRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calcul::class);
    }

    // /**
    //  * @return Calcul[] Returns an array of Calcul objects
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
    public function findOneBySomeField($value): ?Calcul
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function totalEmployeeTernaQuery(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(e.id) as total
            from App:Employee e
            where e.societe = 'Terna' ");
        $res=$query->getResult();
        return $res;
    }
    public function totalEmployeeShapeTekQuery(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(e.id) as total
            from App:Employee e
            where e.societe = 'ShapeTek' ");
        $res=$query->getResult();
        return $res;
    }
    public function nbCongeTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(employee.id) as nbConge
                FROM App:Conge conge , App:Employee employee , App:Historique historique
                WHERE (employee.id=conge.idClient or historique.present ='NON')
                 and employee.id=historique.idEmployee 
                 and employee.societe ='Terna'
                and conge.dateFin >= '$current'
            ");
        $res=$query->getResult();
        return $res;
    }
    public function nbCongeShaptekQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(employee.id) as nbConge
                FROM App:Conge conge , App:Employee employee , App:Historique historique
                WHERE  employee.societe ='ShapeTek'
                  and (employee.id=conge.idClient or historique.present ='NON')
                 and employee.id=historique.idEmployee 
                and conge.dateFin >= '$current'
            ");
        $res=$query->getResult();
        return $res;
    }

    public function nbMaladieTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'Terna'
            and c.motif = 'Maladie'");
        $res=$query->getResult();
        return $res;
    }
    public function nbReposTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'Terna'
            and c.motif = 'Repos'
            ");
        $res=$query->getResult();
        return $res;
    }
    public function nbMaterniteTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'Terna'
            and c.motif = 'Maternité'
            
            ");
        $res=$query->getResult();
        return $res;
    }
    public function nbFamilialeTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'Terna'
            and c.motif = 'Familiale'
            
            ");
        $res=$query->getResult();
        return $res;
    }
    public function nbAbsentTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'Terna'
            and c.motif = 'Aucune Justification'
            
            ");
        $res=$query->getResult();
        return $res;
    }


    public function nbMaladieShTQuery(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'ShapeTek'
            and c.motif = 'Maladie'");
        $res=$query->getResult();
        return $res;
    }
    public function nbReposShTQuery(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'ShapeTek'
            and c.motif = 'Repos'
            ");
        $res=$query->getResult();
        return $res;
    }
    public function nbMaterniteShTQuery(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'ShapeTek'
            and c.motif = 'Maternité'
            
            ");
        $res=$query->getResult();
        return $res;
    }
    public function nbFamilialeShTQuery(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'ShapeTek'
            and c.motif = 'Familiale'
            
            ");
        $res=$query->getResult();
        return $res;
    }
    public function nbAbsentShTQuery(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:Employee e
            where e.id = c.idClient
            and e.societe = 'ShapeTek'
            and c.motif = 'Aucune Justification'
            
            ");
        $res=$query->getResult();
        return $res;
    }

    public function soldeQuery(){

        $query= $this->getEntityManager()
            ->createQuery("
            SELECT e.nom as nom , e.username as prenom , c.nbJours as nbConge 
            FROM App:Conge c , App:User e
            WHERE c.idUser=e.id
            ");
        $res = $query->getResult();
        return $res;
    }


    public function soldeQueryByUser($id){

        $query= $this->getEntityManager()
            ->createQuery("
            SELECT e.nom as nom , e.username as prenom , c.nbJours as nbConge 
            FROM App:Conge c , App:User e
            WHERE c.idUser=e.id
            AND e.id = $id
            ");
        $res = $query->getResult();
        return $res;
    }

}
