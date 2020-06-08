<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RoleController 
{
    public function __construct(RoleRepository $roleRepo, TokenStorageInterface $tokenStorage)
    {
        $this->roleRepo = $roleRepo;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke()
    {
        //Récupération des différents roles 
        $roles = $this->roleRepo->findAll();
        //Initialisation d'un tableau ainsi que d'un compteur d'incrémentation
        $roleArray = [];
        $i = 0;
        //Récupération du role de l'user connecté 
        $userConnectRole = $this->tokenStorage->getToken()->getUser()->getRoles()[0];

        //Contrôles d'affichage des rôles selon le userConnect
        if($userConnectRole === 'ROLE_ADMIN_SYSTEM'){
           foreach($roles as $role){
               if($role->getLibelle() === 'ADMIN' || $role->getLibelle() === 'CAISSIER'){
                   $roleArray[$i] = $role; 
                   $i++;
               }
           }
        }
        elseif($userConnectRole === 'ROLE_ADMIN'){
           foreach($roles as $role){
               if($role->getLibelle() === 'CAISSIER'){
                   $roleArray[$i] = $role;
                   $i++;
               }
           }
        }
        elseif ($userConnectRole === 'ROLE_PARTENAIRE') {
           foreach($roles as $role){
               if($role->getLibelle() === 'ADMIN_PARTNER' || $role->getLibelle() === 'USER_PARTNER'){
                   $roleArray[$i] = $role;
                   $i++;
                }
            }
        }    
        elseif ($userConnectRole === 'ROLE_ADMIN_PARTNER') {
           foreach($roles as $role){
               if($role->getLibelle() === 'USER_PARTNER'){
                   $roleArray[$i] = $role;
                   $i++;
                }
            }
        }
    return $roleArray;    
    }
}       