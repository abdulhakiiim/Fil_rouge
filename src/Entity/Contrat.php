<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ContratRepository")
 */
class Contrat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $termes;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Partenaire", mappedBy="contratObject", cascade={"persist", "remove"})
     */
    private $partObject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTermes(): ?string
    {
        return $this->termes;
    }

    public function setTermes(string $termes): self
    {
        $this->termes = $termes;

        return $this;
    }

    public function getPartObject(): ?Partenaire
    {
        return $this->partObject;
    }

    public function setPartObject(?Partenaire $partObject): self
    {
        $this->partObject = $partObject;

        // set (or unset) the owning side of the relation if necessary
        $newContratObject = null === $partObject ? null : $this;
        if ($partObject->getContratObject() !== $newContratObject) {
            $partObject->setContratObject($newContratObject);
        }

        return $this;
    }
}
