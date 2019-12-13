<?php


namespace AppBundle\Utils;


use Doctrine\ORM\EntityManager;

class GestionFacture
{
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return int|mixed|string
     */
    public function reference()
    {
        $id = $this->em->getRepository("AppBundle:Facture")->getNombreFactrure();
        if ($id < 1) {
            $facture = 1;
        }else{
            $facture = $id;
        }
        if ($facture < 10) $reference = '000000'.$facture;
        elseif ($facture<100) $reference = '00000'.$facture;
        elseif ($facture < 100) $reference = '0000'.$facture;
        elseif ($facture < 1000) $reference = '000'.$facture;
        elseif ($facture < 10000) $reference = '00'.$facture;
        elseif ($facture < 100000) $reference = '0'.$facture;
        else $reference = $facture;

        return $reference;
    }

    /**
     * Mise a jour des montant et du nombre de produit après ajout de vente
     *
     * @param $id
     * @param $montant
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addVente($id,$montant,$addProd=null)
    {
        $facture = $this->em->getRepository("AppBundle:Facture")->findOneBy(['id'=>$id]);
        $facture->setMontant($facture->getMontant()+$montant);
        if ($addProd)$facture->setNombreProduit($facture->getNombreProduit()+1);;
        $this->em->flush();

        return true;
    }
}
