<?php

namespace App\Entity;

use App\Repository\PossedeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PossedeRepository::class)
 */
class Possede
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="possedes")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Carte::class, inversedBy="possedes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carte;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbexemplaires;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarte(): ?Carte
    {
        return $this->carte;
    }

    public function setCarte(?Carte $carte): self
    {
        $this->carte = $carte;

        return $this;
    }

    public function getNbexemplaires(): ?int
    {
        return $this->nbexemplaires;
    }

    public function setNbexemplaires(int $nbexemplaires): self
    {
        $this->nbexemplaires = $nbexemplaires;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
