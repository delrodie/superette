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
        //dump($inventaire->getReference());die();
        if ($inventaire){
            $id = $inventaire->getId() + 1;
            if ($id < 10) $reference = 'A00'.$id;
            elseif ($id<100) $reference = 'A0'.$id;
            else $reference = 'A'.$id;
            return $reference;
        }else{
            $reference = 'A001';
            return $reference;
        }
    }

    /**
     * Deuction du montrant de l'inventaire
     * @param $id
     * @param $montant
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deduction($id, $montant)
    {
        $inventaire = $this->em->getRepository("AppBundle:Inventaire")->findOneBy(['id'=>$id]);
        if ($inventaire){

            $deduit = $inventaire->getDeduction() - $montant;
            $inventaire->setDeduction($deduit);
            $this->em->flush();

            return true;
        }else{
            return false;
        }
    }

    public function deleteDeduction($id,$montant)
    {
        $inventaire = $this->em->getRepository("AppBundle:Inventaire")->findOneBy(['id'=>$id]);
        if ($inventaire){
            $deduit = $inventaire->getDeduction()+$montant;
            $inventaire->setDeduction($deduit);
            $this->em->flush();

            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $id
     * @param $quantite
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addStock($id,$quantite)
    {
        $produit = $this->em->getRepository("AppBundle:Produit")->findOneBy(['id'=>$id]);
        if ($produit){
            $stock = $produit->getStock() + $quantite;
            $produit->setStock($stock);
            $produit->setInventaire(true);
            $this->em->flush();

            return true;
        }else{
            return false;
        }
    }

    /**
     * Deduction du nombre de stock du produit
     *
     * @param $id
     * @param $quantite
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteStock($id,$quantite)
    {
        $produit = $this->em->getRepository("AppBundle:Produit")->findOneBy(['id'=>$id]);
        if ($produit){
            $stock = $produit->getStock() - $quantite;
            $produit->setStock($stock);
            $this->em->flush();

            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $id
     * @param $montant
     * @param $quantite
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePrixAchat($id,$montant,$quantite = null)
    {
        if ($quantite) $new_prixAchat = round($montant/$quantite);
        else $new_prixAchat = $montant;
        $produit = $this->em->getRepository("AppBundle:Produit")->findOneBy(['id'=>$id]);
        if ($produit){
            $old_prixAchat = $produit->getPrixAchat();
            $produit->setPrixAchat($new_prixAchat);
            $produit->setOldPrixAchat($old_prixAchat);
            $this->em->flush();

            return true;
        }else{
            return false;
        }
    }

    /**
     * Augmentation du nombre de produit de l'inventaire concerné
     *
     * @param $id
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addNombreProduit($id)
    {
        $inventaire = $this->em->getRepository("AppBundle:Inventaire")->findOneBy(['id'=>$id]);
        if ($inventaire){
            $produit = $inventaire->getNombreProduit()+1;
            $inventaire->setNombreProduit($produit);
            $this->em->flush();

            return true;
        }else{
            return false;
        }
    }

    /**
     * Deduction du nombre de produit de l'inventaire
     * @param $id
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteNombreProduit($id)
    {
        $inventaire = $this->em->getRepository("AppBundle:Inventaire")->findOneBy(['id'=>$id]);
        if ($inventaire){
            $produit = $inventaire->getNombreProduit()-1;
            if ($produit < 0) $produit = 0;
            $inventaire->setNombreProduit($produit);
            $this->em->flush();

            return true;
        }else{
            return false;
        }
    }
}
