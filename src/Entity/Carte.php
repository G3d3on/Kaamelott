<?php

namespace App\Entity;

use App\Repository\CarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarteRepository::class)
 */
class Carte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $imagePerso;

    /**
     * @ORM\Column(type="smallint")
     */
    private $attaque;

    /**
     * @ORM\Column(type="smallint")
     */
    private $defense;

    /**
     * @ORM\Column(type="string", length=160)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="cartes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;

    /**
     * @ORM\OneToMany(targetEntity=Compose::class, mappedBy="carte", orphanRemoval=true)
     */
    private $composes;

    /**
     * @ORM\OneToMany(targetEntity=Possede::class, mappedBy="carte", orphanRemoval=true)
     */
    private $possedes;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;


    public function __construct()
    {
        $this->composes = new ArrayCollection();
        $this->possedes = new ArrayCollection();
    }

    /**
     * @return Collection|Compose[]
     */
    public function getComposes(): Collection
    {
        return $this->composes;
    }

    public function addCompose(Compose $compose): self
    {
        if (!$this->composes->contains($compose)) {
            $this->composes[] = $compose;
            $compose->setCarte($this);
        }

        return $this;
    }

    public function removeCompose(Compose $compose): self
    {
        if ($this->composes->removeElement($compose)) {
            // set the owning side to null (unless already changed)
            if ($compose->getCarte() === $this) {
                $compose->setCarte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Possede[]
     */
    public function getPossedes(): Collection
    {
        return $this->possedes;
    }

    public function addPossede(Possede $possede): self
    {
        if (!$this->possedes->contains($possede)) {
            $this->possedes[] = $possede;
            $possede->setCarte($this);
        }

        return $this;
    }

    public function removePossede(Possede $possede): self
    {
        if ($this->possedes->removeElement($possede)) {
            // set the owning side to null (unless already changed)
            if ($possede->getCarte() === $this) {
                $possede->setCarte(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getimagePerso(): ?string
    {
        return $this->imagePerso;
    }

    public function setimagePerso(string $imagePerso): self
    {
        $this->imagePerso = $imagePerso;

        return $this;
    }

    public function getImageVerso(): ?string
    {
        return "carte-verso.jpg";
    }

    public function getAttaque(): ?int
    {
        return $this->attaque;
    }

    public function setAttaque(int $attaque): self
    {
        $this->attaque = $attaque;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    
}
