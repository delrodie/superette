<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
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

    /**
     * @Route("/categorie", name="etat_produit_categorie")
     */
    public function categorieAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("AppBundle:Categorie")->liste()->getQuery()->getResult();

        return $this->render("etat/categorie_list.html.twig",[
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/categorie/{id}", name="etat_produit_categorie_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository("AppBundle:Produit")->findBy(['categorie'=>$id]);

        return $this->render("etat/produit_categorie.html.twig",[
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/categorie/{id}/liste", name="etat_produit_categorie_liste")
     * @Method("GET")
     */
    public function detailsAction(Categorie $categorie)
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository("AppBundle:Produit")->findBy(['categorie'=>$categorie->getId()]);

        return $this->render("etat/categorie_details.html.twig",[
            'categorie' => $categorie,
            'produits' => $produits,
        ]);
    }
}
