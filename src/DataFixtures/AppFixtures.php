<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        //Hydratation du role admin_system
        $roleAdminSystem = new Role();
            $roleAdminSystem->setLibelle("ADMIN_SYSTEM");
            $manager->persist($roleAdminSystem);

        //Hydratation du role admin
        $roleAdmin = new Role();
            $roleAdmin->setLibelle("ADMIN");
            $manager->persist($roleAdmin);

        //Hydratation du role caissier
        $roleCaissier = new Role();
            $roleCaissier->setLibelle("CAISSIER");
            $manager->persist($roleCaissier);   
            
        //Hydratation du role partenaire
        $rolePatner = new Role();
            $rolePatner->setLibelle("PARTENAIRE");
            $manager->persist($rolePatner); 
            
        //Hydratation de la table role côté db
        $this->addReference('Admin_System' , $roleAdminSystem);
        $this->addReference('Admin' , $roleAdmin);
        $this->addReference('Caissier' , $roleCaissier);
        $this->addReference('Patner' , $rolePatner );

        //Récupération du role admin_system
        $roleSupadmin= $this->getReference('Admin_System');

        //Créeation user admin_system
        $user = new User();
        $user->setEmail("admin_system@tawfeex.sn")
             ->setNomComplet("Abdul Hakiim")
             ->setPassword($this->encoder->encodePassword($user, "Admin_System"))
             ->setRoles((array("ROLE_".$roleSupadmin->getLibelle())))
             ->setRole($roleAdminSystem)
             ->setIsActive(true);
        $manager->persist($user);

        $manager->flush();
    }
}
