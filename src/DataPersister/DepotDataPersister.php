<?php
namespace App\DataPersister;

use App\Entity\Depot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class DepotDataPersister implements DataPersisterInterface
{
    
    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports($data): bool
    {
        return $data instanceof Depot;
    }

    public function persist($data)
    {
        #############################Faire dépot (controle)##############################
        
        if($data->getId() ==null){

            //Récupération du user qui a effectué le dépot
            $userdepot =  $this->tokenStorage->getToken()->getUser();
            $data->setUserDepot($userdepot);
        };

        //Récupération du montant à déposer et du solde dans le compte réceptionnaire du dépot
        $depot = $data->getMontant();
        $soldecompte = $data->getCompteDepot()->getSolde();

        //Mise à jour du solde compte 
        if($data->getId() ==null){
            $totalsolde = $soldecompte + $depot;
            $data->getCompteDepot()->setSolde($totalsolde);
        }

        
        $this->entityManager->persist($data);
        $this->entityManager->flush();

    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}