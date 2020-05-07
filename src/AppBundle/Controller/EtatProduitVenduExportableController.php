<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/produit-vendu-exportable")
 */
class EtatProduitVenduExportableController extends Controller
{
    /**
     * @Route("/", name="produit_vendu_exportbale")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
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

        // S'il n'y a pas de requete date alors affecter la date encours
        if (!$debut && !$fin){
            $date_debut = date('Y-m-d');
            $date_fin = date('Y-m-d');
        }

        $caissiers = $em->getRepository("AppBundle:User")->findUsers();

        // Liste des ventes
        $ventes = $em->getRepository("AppBundle:Vente")->findList($date_debut,$date_fin,$caissier); //dump($ventes);die();

        return $this->render('AppBundle:EtatProduitVendu:exportable.html.twig', array(
            'ventes' => $ventes,
            'caissiers'=>$caissiers,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin
        ));
    }

}
