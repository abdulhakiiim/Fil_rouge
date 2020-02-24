<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 */
class Partenaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"read" , "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $ninea;

    /**
     * @Groups({"read" , "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $rc;

    /**
     * @Groups({"read" , "write"})
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="partenaire", cascade={"persist", "remove"})
     */
    private $partUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="comptPart")
     */
    private $comptes;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contrat", inversedBy="partObject", cascade={"persist", "remove"})
     */
    private $contratObject;

    public function __construct()
    {
        $this->partUsers = new ArrayCollection();
        $this->comptes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getRc(): ?string
    {
        return $this->rc;
    }

    public function setRc(string $rc): self
    {
        $this->rc= $rc;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getPartUsers(): Collection
    {
        return $this->partUsers;
    }

    public function addPartUser(User $partUser): self
    {
        if (!$this->partUsers->contains($partUser)) {
            $this->partUsers[] = $partUser;
            $partUser->setPartenaire($this);
        }

        return $this;
    }

    public function removePartUser(User $partUser): self
    {
        if ($this->partUsers->contains($partUser)) {
            $this->partUsers->removeElement($partUser);
            // set the owning side to null (unless already changed)
            if ($partUser->getPartenaire() === $this) {
                $partUser->setPartenaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setComptPart($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getComptPart() === $this) {
                $compte->setComptPart(null);
            }
        }

        return $this;
    }

    public function getContratObject(): ?Contrat
    {
        return $this->contratObject;
    }

    public function setContratObject(?Contrat $contratObject): self
    {
        $this->contratObject = $contratObject;

        return $this;
    }
}
