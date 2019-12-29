<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class DepotVenteController
 * @Route("/depot-vente")
 */
class DepotVenteController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirectToRoute("homepage");
    }
}
