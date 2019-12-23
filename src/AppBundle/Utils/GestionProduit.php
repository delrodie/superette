<?php


namespace AppBundle\Utils;


use Doctrine\ORM\EntityManager;

class GestionProduit
{
    public function __construct(EntityManager $entityManager)
    {
        $this->em =$entityManager;
    }

    /**
     * Generation du code du domaine
     */
    public function codeDomaine()
    {
        $nombre_domaine = $this->em->getRepository("AppBundle:Domaine")->getNombreDomaine();

        if ($nombre_domaine){
            $code = 10+$nombre_domaine;
        }else{
            $code = 10;
        }

        return $code;
    }

    /**
     * Generation du code de la categorie
     */
    public function codeCategorie($domaine)
    {
        // s'il existe des categories selon le domaine alors
        $nombre_categorie = $this->em->getRepository("AppBundle:Categorie")->getNombreByDomaine($domaine);
        $domaineCode = $this->em->getRepository("AppBundle:Domaine")->findOneBy(['id'=>$domaine])->getCode();
        $code = $domaineCode.''.$nombre_categorie+1;

        // Vérifciation de la non existence du code
        //Si code existe alors generate un autre code
        $verif = $this->em->getRepository("AppBundle:Categorie")->findBy(['code'=>$code]);
        if ($verif){
            $dernierCode = $this->em->getRepository("AppBundle:Categorie")->findOneBy(['domaine'=>$domaine], ['id'=>'DESC'])->getCode();
            $code = $dernierCode+1;

            return $code;
        }else{
            return $code;
        }

    }

    /**
     * Generation code du produit
     */
    public function codeProduit($categorie)
    {
        //Recuperation du code de la categorie et du nombre de produit enregistré
        $categorieEntity= $this->em->getRepository("AppBundle:Categorie")->findOneBy(['id'=>$categorie]);
        $nombreProduit = $categorieEntity->getNombreProduit();
        $latence = $nombreProduit+1;
        if ($latence < 10) $reference = $categorieEntity->getCode().'00'.$latence;
        elseif ($latence < 100) $reference = $categorieEntity->getCode().'0'.$latence;
        else $reference = $categorieEntity->getCode().''.$latence;

        // Verification de la non existence de la reference du produit
        $code = $this->em->getRepository("AppBundle:Produit")->findOneBy(['reference'=>$reference]);
        while($code){ //die('attraper ici');
            $reference = $reference+1;
            $code = $this->em->getRepository("AppBundle:Produit")->findOneBy(['reference'=>$reference]);
        }
        return $reference;
    }

    /**
     * Ajout de produit a la categorie
     */
    public function addProduit($categorieID)
    {
        $categorie = $this->em->getRepository("AppBundle:Categorie")->findOneBy(['id'=>$categorieID]);
        $categorie->setNombreProduit($categorie->getNombreProduit()+1);
        $this->em->flush();

        return true;
    }

    /**
     * reduction du nombre de produit à la categorie
     */
    public function deleteProduit($categorieID)
    {
        $categorie = $this->em->getRepository("AppBundle:Categorie")->findOneBy(['id'=>$categorieID]);
        $categorie->setNombreProduit($categorie->getNombreProduit()-1);
        $this->em->flush();

        return true;
    }

}
