<?php

namespace App\Entity;

use App\Repository\CalculRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalculRepository::class)
 */
class Calcul
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="integer")
     */
    private $absent;

    /**
     * @ORM\Column(type="integer")
     */
    private $present;

    /**
     * @ORM\Column(type="integer")
     */
    private $enConge;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getAbsent(): ?int
    {
        return $this->absent;
    }

    public function setAbsent(int $absent): self
    {
        $this->absent = $absent;

        return $this;
    }

    public function getPresent(): ?int
    {
        return $this->present;
    }

    public function setPresent(int $present): self
    {
        $this->present = $present;

        return $this;
    }

    public function getEnConge(): ?int
    {
        return $this->enConge;
    }

    public function setEnConge(int $enConge): self
    {
        $this->enConge = $enConge;

        return $this;
    }
}
