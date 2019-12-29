<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facture;
use AppBundle\Utils\GestionFacture;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ImpressionController
 * @Route("/impression")
 */
class ImpressionController extends Controller
{
    /**
     * @Route("/{facture}", name="impression_facture")
     * @Method({"GET","POST"})
     */
    public function factureAction(Request $request, Facture $facture, GestionFacture $gestionFacture)
    {
        $em = $this->getDoctrine()->getManager();
        $verse = $request->get('verse');
        $monnaie = $request->get('monnaie');
        $reduction = $request->get('facturation_reduction');
        $montantReduit = $request->get('facturation_total');
        $ventes = $em->getRepository("AppBundle:Vente")->findBy(['facture'=>$facture->getId()]);

        $gestionFacture->finalisation($facture->getId(),$verse,$monnaie,$reduction,$montantReduit);

        return $this->render('etat/facture3.html.twig',[
            'facture' => $facture,
            'ventes' => $ventes,
        ]);

    }
}
