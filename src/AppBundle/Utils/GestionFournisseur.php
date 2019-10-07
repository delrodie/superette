<?php


namespace AppBundle\Utils;


use Doctrine\ORM\EntityManager;

class GestionFournisseur
{
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Generation du code du fournisseur
     */
    public function code()
    {
        $code = $this->em->getRepository("AppBundle:Fournisseur")->getCodeFournisseur();
        return $code;
    }
}
