<?php

namespace App\Utils;

use App\Repository\CompteRepository;

class NumCompteAuto{

    private $numCompte;

    public function __construct(CompteRepository $compteRepo)
    {
        $this->compteRepo = $compteRepo;
    }

    public function generatenumcompte()
    {
        $lastCompte = $this->compteRepo->findOneBy([],[]);

        if ($lastCompte != null) {
            $lastId = $lastCompte->getId();

            $this->numCompte = "TFC".sprintf("%'.06d",++$lastId);

        }
        else{
            $this->numCompte = "TFC".sprintf("%'.06d",1);   
        }
        
        return $this->numCompte;
    }

}








