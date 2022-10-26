<?php

namespace App\Entity;

use App\Repository\ComposeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComposeRepository::class)
 */
class Compose
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Deck::class, inversedBy="composes")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $deck;

    /**
     * @ORM\ManyToOne(targetEntity=Carte::class, inversedBy="composes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carte;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbexemplaires;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    public function setDeck(?Deck $deck): self
    {
        $this->deck = $deck;

        return $this;
    }

   
    
}
