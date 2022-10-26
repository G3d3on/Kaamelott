<?php

namespace App\Entity;


use App\Repository\PartieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartieRepository::class)
 */
class Partie 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Joue::class, mappedBy="partie")
     */
    private $joues;

    protected $clients;

    public function __construct()
    {
        $this->joues = new ArrayCollection();
        $this->clients = new \SplObjectStorage;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Joue[]
     */
    public function getJoues(): Collection
    {
        return $this->joues;
    }

    public function addJoue(Joue $joue): self
    {
        if (!$this->joues->contains($joue)) {
            $this->joues[] = $joue;
            $joue->setPartie($this);
        }

        return $this;
    }

    public function removeJoue(Joue $joue): self
    {
        if ($this->joues->removeElement($joue)) {
            // set the owning side to null (unless already changed)
            if ($joue->getPartie() === $this) {
                $joue->setPartie(null);
            }
        }

        return $this;
    }
}
