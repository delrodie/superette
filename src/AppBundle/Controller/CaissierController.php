<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facture;
use Cassandra\Date;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CaissierController
 * @Route("/Caissiere")
 */
class CaissierController extends Controller
{
    /**
     * @Route("/", name="caissier_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $today = \Date('Y-m-d', time());

        $factures = $em->getRepository("AppBundle:Facture")->findByPeriode($today,$today,$user->getUsername());

        return $this->render("default/caissier_list.html.twig",[
            'factures' => $factures,
        ]);
    }

    /**
     * @Route("/{id}", name="caissier_show")
     * @Method("GET")
     */
    public function showAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $today = \Date('Y-m-d', time());

        $factures = $em->getRepository("AppBundle:Facture")->findByPeriode($today,$today,$user->getUsername());
        $ventes = $em->getRepository("AppBundle:Vente")->findBy(['facture'=>$facture->getId()]);

        return $this->render("default/caissier_show.html.twig",[
            'factures' => $factures,
            'caissier' => $facture->getPubliePar(),
            'ventes' => $ventes,
            'facture' => $facture,
        ]);
    }
}
