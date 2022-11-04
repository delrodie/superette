<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Inventaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class EtatInventaireController*
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/etat/inventaire")
 */
class EtatInventaireController extends Controller
{
    /**
     * @Route("/", name="etat_inventaire_liste")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $inventaires = $em->getRepository("AppBundle:Inventaire")->findDesc();

        return $this->render("etat/inventaire_list.html.twig",[
            'inventaires' => $inventaires
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{id}", name="etat_inventaire_show")
     * @Method("GET")
     */
    public function showAction(Inventaire $inventaire)
    {
        $em = $this->getDoctrine()->getManager();

        $inventoriers = $em->getRepository("AppBundle:Inventorier")->findBy(['inventaire'=>$inventaire->getId()]);

        return $this->render("etat/inventaire_details.html.twig",[
            'inventoriers' => $inventoriers,
            'inventaire' => $inventaire
        ]);
    }
}
