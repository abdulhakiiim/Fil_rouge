<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *       collectionOperations={
 *                            "get"={"security"="is_granted('ROLE_CAISSIER')"}, 
 *                            "post"={
 *                               "security"="is_granted('ROLE_CAISSIER')",
 *                               "method"="POST"}},
 *       itemOperations={
 *                      "get"={"security"="is_granted('ROLE_CAISSIER')"}},
 *       normalizationContext={"groups"={"depot_read"}},
 *       denormalizationContext={"groups"={"depot_write"}})
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 */
class Depot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"depot_read" , "depot_write"})
     * @Groups({"read" , "write"})
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="depots")
     */
    private $userDepot;

    /**
     * @Groups({"depot_read" , "depot_write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depots")
     */
    private $compteDepot;

    /**
     * @Groups("read")
     * @ORM\Column(type="date")
     */
    private $dateDepot;

    public function __construct()
    {
        $this->dateDepot = new \DateTime;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getUserDepot(): ?User
    {
        return $this->userDepot;
    }

    public function setUserDepot(?User $userDepot): self
    {
        $this->userDepot = $userDepot;

        return $this;
    }

    public function getCompteDepot(): ?Compte
    {
        return $this->compteDepot;
    }

    public function setCompteDepot(?Compte $compteDepot): self
    {
        $this->compteDepot = $compteDepot;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }
}
