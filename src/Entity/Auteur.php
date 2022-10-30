<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 */
class Auteur
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
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=JeuSociete::class, mappedBy="auteur_jeu")
     */
    private $creations;

    public function __construct()
    {
        //$this->crees = new ArrayCollection();
        $this->creations = new ArrayCollection();
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

    /**
     * @return Collection<int, JeuSociete>
     */
    public function getCreations(): Collection
    {
        return $this->creations;
    }

    public function addCreation(JeuSociete $creation)
    {
        if ($this->creations->contains($creation)) {
            return;
        }

        $this->auteur_jeu->add($creation);
        $creation->addAuteurJeu($this);
    }

    public function removeCreation(JeuSociete $creation)
    {
        if(!$this->creations->contains($creation)){
            return;
        }

        $this->auteurJeu->removeElement($creation);
        $creation->removeAuteurJeu($this);
    }

    public function __toString()
    {
        return $this->nom;
    }

}
