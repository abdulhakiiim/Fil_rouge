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
            $roleAdminSystem->setLibelle("ROLE_ADMIN_SYSTEM");
            $manager->persist($roleAdminSystem);

        //Hydratation du role admin
        $roleAdmin = new Role();
            $roleAdmin->setLibelle("ROLE_ADMIN");
            $manager->persist($roleAdmin);

        //Hydratation du role caissier
        $roleCaissier = new Role();
            $roleCaissier->setLibelle("ROLE_CAISSIER");
            $manager->persist($roleCaissier);    
            
        //Hydratation de la table role côté db
        $this->addReference('Admin_System' , $roleAdminSystem);
        $this->addReference('Admin' , $roleAdmin);
        $this->addReference('Caissier' , $roleCaissier);


        $user = new User();
        $user->setEmail("admin_system@tawfeex.sn")
             ->setNomComplet("Abdul Hakiim")
             ->setPassword($this->encoder->encodePassword($user, "Admin_System"))
             ->setRoles(json_encode(array("ROLE_ADMIN_SYSTEM, ROLE_ADMIN, ROLE_CAISSIER")))
             ->setRole($roleAdminSystem)
             ->setIsActif("1");
        $manager->persist($user);

        $manager->flush();
    }
}
