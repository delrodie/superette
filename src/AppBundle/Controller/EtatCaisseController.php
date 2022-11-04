<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facture;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class EtatCaisseController
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/etat/caisse")
 */
class EtatCaisseController extends Controller
{
    /**
     * @Route("/", name="etat_caisse_liste")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factures = $em->getRepository("AppBundle:Facture")->findListeDESC();
        $caissiers = $em->getRepository("AppBundle:User")->findUsers();

        return $this->render("etat/facture_list.html.twig",[
            'factures'=>$factures,
            'caissiers' =>$caissiers
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user", name="etat_caisse_show")
     * @Method({"GET","POST"})
     */
    public function showAction(Request $request)
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

        return $this->render("etat/facture_details.html.twig",[
            'factures' => $factures,
            'debut' => $date_debut,
            'fin' => $date_fin,
            'caissiers'=>$caissiers,
            'caissier' => $caissier
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/facture/{id}", name="etat_caisse_vente")
     * @Method("GET")
     */
    public function venteAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $ventes = $em->getRepository("AppBundle:Vente")->findBy(['facture'=>$facture->getId()]);

        return $this->render("etat/vente.html.twig",[
            'ventes' => $ventes,
            'facture' => $facture
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{user}/{debut}/{fin}", name="etat_caisse_details")
     * @Method("GET")
     */
    public function derailsAction($user,$debut,$fin)
    {
        $em = $this->getDoctrine()->getManager();

        $factures = $em->getRepository("AppBundle:Facture")->findByPeriode($debut,$fin,$user);
        $caissiers = $em->getRepository("AppBundle:User")->findUsers();

        return $this->render("etat/facture_show.html.twig",[
            'factures' => $factures,
            'debut' => $debut,
            'fin' => $fin,
            'caissiers'=>$caissiers,
            'caissier' => $user
        ]);
    }
}
