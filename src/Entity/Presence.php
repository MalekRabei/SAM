<?php

namespace App\Entity;

use App\Repository\PresenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PresenceRepository::class)
 */
class Presence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $present;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $retard;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePresence;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRetard;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="idUser",referencedColumnName="id")
     *
     */
    private $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPresent(): ?string
    {
        return $this->present;
    }

    public function setPresent(string $present): self
    {
        $this->present = $present;

        return $this;
    }

    public function getRetard(): ?string
    {
        return $this->retard;
    }

    public function setRetard(string $retard): self
    {
        $this->retard = $retard;

        return $this;
    }


    public function getDateRetard(): ?\DateTimeInterface
    {
        return $this->dateRetard;
    }

    public function setDateRetard(\DateTimeInterface $dateRetard): self
    {
        $this->dateRetard = $dateRetard;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getDatePresence()
    {
        return $this->datePresence;
    }

    /**
     * @param mixed $datePresence
     */
    public function setDatePresence($datePresence): void
    {
        $this->datePresence = $datePresence;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser): void
    {
        $this->idUser = $idUser;
    }
}
