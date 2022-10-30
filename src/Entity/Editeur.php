<?php

namespace App\Entity;

use App\Repository\EditeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EditeurRepository::class)
 */
class Editeur
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
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=JeuSociete::class, mappedBy="editeur")
     */
    private $jeuxSociete;

    public function __construct()
    {
        $this->jeuxSociete = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, JeuSociete>
     */
    public function getJeuxSocietes(): Collection
    {
        return $this->jeuxSociete;
    }

    public function addJeuSociete(JeuSociete $jeuxSociete): self
    {
        if (!$this->jeuxSociete->contains($jeuxSociete)) {
            $this->jeuxSociete[] = $jeuxSociete;
            $jeuxSociete->setEditeur($this);
        }

        return $this;
    }

    public function removeJeuSociete(JeuSociete $jeuxSociete): self
    {
        if ($this->jeuxSociete->removeElement($jeuxSociete)) {
            // set the owning side to null (unless already changed)
            if ($jeuxSociete->getEditeur() === $this) {
                $jeuxSociete->setEditeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, JeuSociete>
     */
    public function getJeuxSociete(): Collection
    {
        return $this->jeuxSociete;
    }

    public function addJeuxSociete(JeuSociete $jeuxSociete): self
    {
        if (!$this->jeuxSociete->contains($jeuxSociete)) {
            $this->jeuxSociete[] = $jeuxSociete;
            $jeuxSociete->setEditeur($this);
        }

        return $this;
    }

    public function removeJeuxSociete(JeuSociete $jeuxSociete): self
    {
        if ($this->jeuxSociete->removeElement($jeuxSociete)) {
            // set the owning side to null (unless already changed)
            if ($jeuxSociete->getEditeur() === $this) {
                $jeuxSociete->setEditeur(null);
            }
        }

        return $this;
    }
}
