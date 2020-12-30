<?php

namespace App\Entity;

use App\Repository\UserRepository;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $societe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $t;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $presence;

    /**
     * @ORM\ManyToOne(targetEntity="Poste")
     * @ORM\JoinColumn(name="idPoste",referencedColumnName="id" , nullable=true)
     *
     */
    private $idPoste;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * @param mixed $profession
     */
    public function setProfession($profession): void
    {
        $this->profession = $profession;
    }

    /**
     * @return mixed
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * @param mixed $societe
     */
    public function setSociete($societe): void
    {
        $this->societe = $societe;
    }

    /**
     * @return mixed
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * @param mixed $poste
     */
    public function setPoste($poste): void
    {
        $this->poste = $poste;
    }

    /**
     * @return mixed
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * @param mixed $matricule
     */
    public function setMatricule($matricule): void
    {
        $this->matricule = $matricule;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getT()
    {
        return $this->t;
    }

    /**
     * @param mixed $t
     */
    public function setT($t): void
    {
        $this->t = $t;
    }

    /**
     * @return mixed
     */
    public function getPresence()
    {
        return $this->presence;
    }

    /**
     * @param mixed $presence
     */
    public function setPresence($presence): void
    {
        $this->presence = $presence;
    }

    /**
     * @return mixed
     */
    public function getIdPoste()
    {
        return $this->idPoste;
    }

    /**
     * @param mixed $idPoste
     */
    public function setIdPoste($idPoste): void
    {
        $this->idPoste = $idPoste;
    }
}
