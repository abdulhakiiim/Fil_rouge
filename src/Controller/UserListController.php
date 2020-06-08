<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserListController 
{
    public function __construct(UserRepository $userRepo, TokenStorageInterface $tokenStorage)
    {
        $this->userRepo = $userRepo;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke()
    {
        //Récupération des différents user
        $users = $this->userRepo->findAll();
        //Initialisation d'un tableau ainsi que d'un compteur d'incrémentation
        $userArray = [];
        $i = 0;
        //Récupération du role de l'user connecté 
        $userConnectRole = $this->tokenStorage->getToken()->getUser()->getRoles()[0];

        //Contrôles d'affichage des users selon le userConnect
        if($userConnectRole === 'ROLE_ADMIN_SYSTEM'){
           foreach($users as $user){
               if($user->getRole()->getLibelle() === 'ADMIN' || $user->getRole()->getLibelle() === 'CAISSIER'){
                   $userArray[$i] = $user; 
                   $i++;
               }
           }
        }
        elseif($userConnectRole === 'ROLE_ADMIN'){
           foreach($users as $user){
               if($user->getRole()->getLibelle() === 'CAISSIER'){
                   $userArray[$i] = $user;
                   $i++;
               }
           }
        }
        elseif ($userConnectRole === 'ROLE_PARTENAIRE') {
           foreach($users as $user){
               if($user->getRole()->getLibelle() === 'ADMIN_PARTNER' || $user->getRole()->getLibelle() === 'USER_PARTNER'){
                   $userArray[$i] = $user;
                   $i++;
                }
            }
        }    
        elseif ($userConnectRole === 'ROLE_ADMIN_PARTNER') {
           foreach($users as $user){
               if($user->getRole()->getLibelle() === 'USER_PARTNER'){
                   $userArray[$i] = $user;
                   $i++;
                }
            }
        }
    return $userArray;    
    }
}       