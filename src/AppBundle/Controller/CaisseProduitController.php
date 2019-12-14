<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CaisseProduitController
 * @Route("/liste-produit")
 */
class CaisseProduitController extends Controller
{
    /**
     * @Route("/", name="liste_produit")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager();
        $produits = $em->getRepository("AppBundle:Produit")->findAll();

        return $this->render("produit/produit_liste.html.twig",[
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/caisse", name="caisse_jour")
     */
    public function caisseAction()
    {
        $em =$this->getDoctrine()->getManager();
        $factures = $em->getRepository("AppBundle:Facture")->findAll();

        return $this->render("produit/facture_list.html.twig",[
            'factures' => $factures
        ]);
    }
}
