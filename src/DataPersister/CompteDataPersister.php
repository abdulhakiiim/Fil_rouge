<?php
namespace App\DataPersister;

use App\Entity\Compte;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;


class CompteDataPersister implements DataPersisterInterface
{
    
    public function __construct(EntityManagerInterface $entityManager, ContratRepository $contratRepo)
    {
        $this->entityManager = $entityManager;
        $this->contratRepo = $contratRepo;
    }

    public function supports($data): bool
    {
        return $data instanceof Compte;
        // TODO: Implement supports() method.
    }

    public function persist($data)
    {
        #############################Générer un contrat pour un nouveau partenaire (contôle)##############################

        //Récupération des infos du nouveau partenaire
        $nompart = $data->getPartObject()->getPartUsers()[0]->getNomComplet();
        $ninea =  $data->getPartObject()->getNinea();
        $rc =  $data->getPartObject()->getRc();

        //$subject stocke les termes du contrat, $search stocke les mots à chercher dans $subject 
        //et $replace stoke les infos du nouveau partenaire qui remplaceront les occurrences de $search
        $subject = $this->contratRepo->findOneBy([],[])->getTermes();
        $search = ["#nom","#ninea","#rc"];
        $replace = [$nompart,$ninea,$rc];

        if($data->getPartObject()->getId() ==null){
        $generate = str_replace($search,$replace,$subject);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return new JsonResponse($generate);
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}