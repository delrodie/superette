<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class EtatProduitController
 * @Route("/etat/produit")
 */
class EtatProduitController extends Controller
{
    /**
     * @Route("/", name="etat_produit_liste")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository("AppBundle:Produit")->findAll();

        return $this->render("etat/produit_list.html.twig",[
            'produits' => $produits,
        ]);
    }
}
