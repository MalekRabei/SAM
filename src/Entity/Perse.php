<?php

namespace App\Entity;

use App\Repository\PerseRepository;
use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\NotifiableInterface;
use Mgilet\NotificationBundle\Annotation\Notifiable;

/**
 * @ORM\Entity(repositoryClass=PerseRepository::class)
 * @Notifiable(name="perse")
 */
class Perse implements NotifiableInterface
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
    private $numPerse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $note;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePerse;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="idEmployee",referencedColumnName="id")
     *
     */
    private $idEmployee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumPerse(): ?string
    {
        return $this->numPerse;
    }

    public function setNumPerse(string $numPerse): self
    {
        $this->numPerse = $numPerse;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDatePerse(): ?\DateTimeInterface
    {
        return $this->datePerse;
    }

    public function setDatePerse(\DateTimeInterface $datePerse): self
    {
        $this->datePerse = $datePerse;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdEmployee()
    {
        return $this->idEmployee;
    }

    /**
     * @param mixed $idEmployee
     */
    public function setIdEmployee($idEmployee): void
    {
        $this->idEmployee = $idEmployee;
    }
}
