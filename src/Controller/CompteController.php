<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Utils\NumCompteAuto;
use App\Repository\RoleRepository;
use App\Repository\CompteRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CompteController
{

    public function __construct(NumCompteAuto $numCompte, TokenStorageInterface $tokenStorage, 
                                 UserPasswordEncoderInterface $userPasswordEncoder, RoleRepository $roleRepo)
    {
        $this->numCompte = $numCompte;
        $this->tokenStorage = $tokenStorage;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->roleRepo = $roleRepo;
    }

    public function __invoke(Compte $data): Compte
    {
        //Encodage password et hydratation du ROLE pour un nouveau partenaire 
        if ($data->getPartObject()->getId() ==null){
            $data->getPartObject()->getPartUsers()[0]->setPassword($this->userPasswordEncoder
                 ->encodePassword($data->getPartObject()->getPartUsers()[0], $data->getPartObject()->getPartUsers()[0]->getPassword()));

            $rolepart=$this->roleRepo->findByRolePart('PARTENAIRE');
            $data->getPartObject()->getPartUsers()[0]->setRole($rolepart[0]);

            $libpart = $data->getPartObject()->getPartUsers()[0]->getRole()->getLibelle();
            $data->getPartObject()->getPartUsers()[0]->setRoles(["ROLE_".$libpart]);
        }

        //Pour un nouveau compte: attribuer un numéro de compte automatique, initialiser le solde, récupération du usercreator
        if($data->getId() ==null){
            $nCompte = $this->numCompte->generatenumcompte();
            $data->setNumCompte($nCompte);

            $firstdepot = $data->getDepots()[0]->setMontant(500000);
            $data->setSolde($firstdepot->getMontant());

            $ucreator =  $this->tokenStorage->getToken()->getUser();
            $data->setUserCreator($ucreator);     

            $userdepot =  $this->tokenStorage->getToken()->getUser();
            $data->getDepots()[0]->setUserDepot($userdepot);
        }

        return $data;
    }
}