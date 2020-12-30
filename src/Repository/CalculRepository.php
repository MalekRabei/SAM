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


    public function totalEmployeeTernaQuery(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(e.id) as total
            from App:User e
            where e.societe = 'Terna' ");
        $res=$query->getResult();
        return $res;
    }
    public function totalEmployeeShapeTekQuery(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(e.id) as total
            from App:User e
            where e.societe = 'ShapeTek' ");
        $res=$query->getResult();
        return $res;
    }

    public function nbCongeTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(conge.id) as nbConge
                FROM App:Conge conge , App:User employee 
                WHERE employee.id = conge.idUser 
                and employee.societe = 'Terna'
                and conge.etat='Validé'
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
                FROM App:Conge conge , App:User employee 
                WHERE  employee.societe ='ShapeTek'
                  and employee.id=conge.idUser
                  and conge.etat='Validé'
                and conge.dateFin >= '$current'
            ");
        $res=$query->getResult();
        return $res;
    }

    public function nbMaladieTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $word='Maladie';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
            and e.societe = 'Terna'
            and c.etat='Validé'
           and c.motif LIKE :word ")
            ->setParameter('word', '%'.$word.'%');
        $res=$query->getResult();
        return $res;
    }
    public function nbReposTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $word= 'Repos';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
            and e.societe = 'Terna'
             and c.etat='Validé'
            and c.motif LIKE :word")
            ->setParameter('word', '%'.$word.'%');
        $res=$query->getResult();
        return $res;
    }
    public function nbMaterniteTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $word = 'Maternite';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
             and c.etat='Validé'
            and e.societe = 'Terna'
            and c.motif LIKE :word
            
            ")
            ->setParameter('word', '%'.$word.'%')
        ;
        $res=$query->getResult();
        return $res;
    }
    public function nbFamilialeTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $word= 'Familiale';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
             and c.etat='Validé'
            and e.societe = 'Terna'
            and c.motif LIKE :word")
            ->setParameter('word', '%'.$word.'%');
        $res=$query->getResult();
        return $res;
    }
    public function nbAbsentTernaQuery(){
        $sysdate = new \DateTime('now');
        $current =$sysdate->format("Y-m-d");
        $word='Aucune Justificatiion';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
             and c.etat='Validé'
            and e.societe = 'Terna'
             and c.motif LIKE :word")
            ->setParameter('word', '%'.$word.'%');
        $res=$query->getResult();
        return $res;
    }


    public function nbMaladieShTQuery(){
        $word = 'Maladie';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
             and c.etat='Validé'
            and e.societe = 'ShapeTek'
             and c.motif LIKE :word ")
            ->setParameter('word', '%'.$word.'%');
        $res=$query->getResult();
        return $res;
    }
    public function nbReposShTQuery(){
        $word = 'Repos';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
             and c.etat='Validé'
            and e.societe = 'ShapeTek'
             and c.motif LIKE :word")
            ->setParameter('word', '%'.$word.'%');
        $res=$query->getResult();
        return $res;
    }
    public function nbMaterniteShTQuery(){
        $word = 'Maternite';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
             and c.etat='Validé'
            and e.societe = 'ShapeTek'
             and c.motif LIKE :word")
            ->setParameter('word', '%'.$word.'%');
        $res=$query->getResult();
        return $res;
    }
    public function nbFamilialeShTQuery(){
        $word='Familiale';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
             and c.etat='Validé'
            and e.societe = 'ShapeTek'
             and c.motif LIKE :word")
            ->setParameter('word', '%'.$word.'%');
        $res=$query->getResult();
        return $res;
    }
    public function nbAbsentShTQuery(){
       $word = 'Aucune Justification';
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(c.id) as nbConge
            from App:Conge c , App:User e
            where e.id = c.idUser
             and c.etat='Validé'
            and e.societe = 'ShapeTek'
            and c.motif LIKE :word")
            ->setParameter('word', '%'.$word.'%');
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
