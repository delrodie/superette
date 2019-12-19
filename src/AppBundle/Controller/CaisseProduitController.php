<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facture;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

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
     * @Method({"GET","POST"})
     */
    public function caisseAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager();
        $date_debut = null; $date_fin = null; $caissier = null;
        $debut = $request->get('date_debut');
        $fin = $request->get('date_fin');
        $caissier = $request->get('caissier');
        if ($debut){
            $date1 = explode('/',$debut);
            $date_debut = $date1[2].'-'.$date1[0].'-'.$date1[1];
        }
        if ($fin){
            $date2 = explode('/',$fin);
            $date_fin = $date2[2].'-'.$date2[0].'-'.$date2[1];
        }

        $factures = $em->getRepository("AppBundle:Facture")->findByPeriode($date_debut,$date_fin,$caissier);
        $caissiers = $em->getRepository("AppBundle:User")->findUsers();
        //dump($factures);die();

        return $this->render("produit/facture_list.html.twig",[
            'factures' => $factures,
            'debut' => $date_debut,
            'fin' => $date_fin,
            'caissiers' => $caissiers,
            'caissier' => $caissier,
        ]);
    }

    /**
     * @Route("/caisse/{id}", name="caisse_facture_delete")
     */
    public function deleteFactureAction(Request $request, Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $facture->setStatut(true);
        $em->flush();

        return $this->redirectToRoute('caisse_jour');
    }
}
