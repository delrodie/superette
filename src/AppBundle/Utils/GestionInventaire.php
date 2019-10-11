<?php


namespace AppBundle\Utils;


use Doctrine\ORM\EntityManager;

class GestionInventaire
{
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Generation de code de l'inventaire
     */
    public function code()
    {
        $inventaire = $this->em->getRepository("AppBundle:Inventaire")->findOneBy(['statut'=>true], ['id'=>'DESC']);
        if ($inventaire){
            $reference = $inventaire->getReference()+1;
            return $reference;
        }else{
            $reference = 'A001';
            return $reference;
        }
    }
}
