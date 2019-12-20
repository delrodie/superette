<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CalculController
 * @Route("/calcul")
 */
class CalculController extends Controller
{

    /**
     * Recette du jour
     * @Route("/{date}", name="etat_caisse_recette_jour")
     * @Method("GET")
     */
    public function recetteAction($date)
    {
        $montant = $this->getDoctrine()->getManager()
            ->getRepository("AppBundle:Facture")
            ->getRecette($date)
        ;

        return $this->render("default/nombre.html.twig",[
            'nombre' => $montant,
        ]);
    }

    /**
     * cumul du nombre de produit du jour
     * @Route("/{date}/nombre", name="etat_nombre_produit_jour")
     * @Method("GET")
     */
    public function nombreProduitAction($date)
    {
        $nombre = $this->getDoctrine()->getmanager()
                        ->getRepository("AppBundle:Facture")
                        ->getNombreproduit($date)
            ;

        return $this->render('default/nombre.html.twig',[
            'nombre' => $nombre
        ]);
    }
}
