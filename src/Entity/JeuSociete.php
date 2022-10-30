<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JeuSocieteRepository::class)
 */
class JeuSociete
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     */
    private $ageMin;

    /**
     * @ORM\Column(type="smallint")
     */
    private $nbJoueurMin;

    /**
     * @ORM\Column(type="smallint")
     */
    private $nbJoueurMax;

    /**
     * @ORM\Column(type="integer")
     */
    private $dureeEstimee;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="jeuxSociete")
     * @ORM\JoinColumn(nullable=false)
     */
    private $editeur;

    /**
     * @ORM\ManyToMany(targetEntity=Auteur::class, inversedBy="creations")
     */
    private $auteur_jeu;

    public function __construct()
    {
        //$this->crees = new ArrayCollection();
        $this->auteur_jeu = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getAgeMin(): ?int
    {
        return $this->ageMin;
    }

    public function setAgeMin(int $ageMin): self
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    public function getNbJoueurMin(): ?int
    {
        return $this->nbJoueurMin;
    }

    public function setNbJoueurMin(int $nbJoueurMin): self
    {
        $this->nbJoueurMin = $nbJoueurMin;

        return $this;
    }

    public function getNbJoueurMax(): ?int
    {
        return $this->nbJoueurMax;
    }

    public function setNbJoueurMax(int $nbJoueurMax): self
    {
        $this->nbJoueurMax = $nbJoueurMax;

        return $this;
    }

    public function getDureeEstimee(): ?int
    {
        return $this->dureeEstimee;
    }

    public function setDureeEstimee(int $dureeEstimee): self
    {
        $this->dureeEstimee = $dureeEstimee;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    /**
     * @return Collection<int, Auteur>
     */
    public function getAuteurJeu(): Collection
    {
        return $this->auteur_jeu;
    }

    public function addAuteurJeu(Auteur $auteurJeu)
    {
        if (!$this->auteur_jeu->contains($auteurJeu)) {
            $this->auteurJeu = $auteurJeu;
            $auteurJeu->addCreation($this);
        }

        return $this;
    }

    public function removeAuteurJeu(Auteur $auteurJeu)
    {
        if($this->auteurJeu->contains($auteurJeu)){
            $this->auteurJeu->removeElement($auteurJeu);
            $auteurJeu->removeCreation($this);
        }
        return $this;
    }

}
