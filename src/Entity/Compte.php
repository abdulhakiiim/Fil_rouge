<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\CompteController;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      collectionOperations={
 *                            "get"={"security"="is_granted('ROLE_CAISSIER')"}, 
 *                            "post"={
 *                               "security"="is_granted('ROLE_ADMIN')",
 *                               "method"="POST",
 *                               "controller"=CompteController::class}},
 *      itemOperations={
 *                      "get"={"security"="is_granted('ROLE_CAISSIER')"}, 
 *                      "put"={
 *                        "security"="is_granted('ROLE_ADMIN')",
 *                        "method"="PUT",
 *                        "controller"=CompteController::class}, 
 *                      "delete"},
 *      normalizationContext={"groups"={"read"}},
 *      denormalizationContext={"groups"={"write"}})
 * @ORM\Entity(repositoryClass="App\Repository\CompteRepository")
 */
class Compte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ApiFilter(SearchFilter::class, properties={"numCompte"})
     * @Groups("read")
     * @ORM\Column(type="string", length=255)
     */
    private $numCompte;

    /**
     * @Groups({"read" , "write"})
     * @ORM\Column(type="integer")
     */
    private $solde;

    /**
     * @ApiFilter(SearchFilter::class, properties={"partObject.ninea"})
     * @Groups({"read" , "write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="comptes", cascade={"persist", "remove" })
     */
    private $partObject;

    /**
     * @Groups("read")
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comptes", cascade={"persist", "remove"})
     */
    private $userCreator;

    /**
     * @Groups({"read" , "write"})
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="compteDepot", cascade={"persist", "remove"})
     */
    private $depots;


    /**
     * @Groups("read")
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->dateCreation = new \DateTime;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCompte(): ?string
    {
        return $this->numCompte;
    }

    public function setNumCompte(string $numCompte): self
    {
        $this->numCompte = $numCompte;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getPartObject(): ?Partenaire
    {
        return $this->partObject;
    }

    public function setPartObject(?Partenaire $partObject): self
    {
        $this->partObject = $partObject;

        return $this;
    }

    public function getUserCreator(): ?User
    {
        return $this->userCreator;
    }

    public function setUserCreator(?User $userCreator): self
    {
        $this->userCreator = $userCreator;

        return $this;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setCompteDepot($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getCompteDepot() === $this) {
                $depot->setCompteDepot(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
