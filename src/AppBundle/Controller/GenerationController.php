<?php

namespace AppBundle\Controller;

use AppBundle\Utils\GestionProduit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class GenerationController
 * @Route("/generation")
 */
class GenerationController extends Controller
{
    /**
     * @Route("/", name="generation_code_produit")
     */
    public function generationAction(GestionProduit $gestionProduit)
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository("AppBundle:Produit")->findByReferenceIsNull(); //dump($produits);die();
        foreach ($produits as $keys => $produit){//dump($produit);die();
            $reference = $gestionProduit->codeProduit($produit->getCategorie()->getId());
            $produit->setReference($reference);
            $em->flush();
            $gestionProduit->addProduit($produit->getCategorie()->getId());
        }
        unset($produit);

        return $this->redirectToRoute("liste_produit");
    }

    /**
     * @Route("/clear", name="generation_code_clear")
     */
    public function clearAction()
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository("AppBundle:Produit")->findAll();
        foreach ($produits as $keys => $produit){
            $vide = null;
            $produit->setReference($vide);
            $em->flush();
        }
        unset($produit);

        $categories = $em->getRepository("AppBundle:Categorie")->findAll();
        foreach ($categories as $keys => $categorie){
            $vide = null;
            $categorie->setNombreProduit($vide);
            $em->flush();
        }
        unset($categorie);

        return $this->redirectToRoute("liste_produit");
    }
}
