<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Produit;
use AppBundle\Utils\GestionProduit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Produit controller.
 *
 * @Route("produit")
 */
class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="produit_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, GestionProduit $gestionProduit)
    {
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('AppBundle:Produit')->findAll();
        $produit = new Produit();
        $form = $this->createForm('AppBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Verification d'existence du produit
            $verif = $em->getRepository("AppBundle:Produit")->findOneBy(['libelle'=>$produit->getLibelle()]);
            if ($verif){
                $this->addFlash('error', "Ce produit existe déja dans le système");
                return $this->redirectToRoute('produit_index');
            }
            $code = $gestionProduit->codeProduit($produit->getCategorie()->getId());
            $produit->setReference($code);
            $em->persist($produit);
            $em->flush();

            if ($gestionProduit->addProduit($produit->getCategorie()->getId())){
                $this->addFlash('notice', "le produit <strong>".$produit->getLibelle()."</strong> a été ajouté avec succès");
            }else{
                $this->addFlash('error', "Attention le produit n'a pas été ajouté à la catégorie.<br> Veuillez contacter le Developpeur!");
            }

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,
            'produit' => $produit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new produit entity.
     *
     * @Route("/new", name="produit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm('AppBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_show', array('id' => $produit->getId()));
        }

        return $this->render('produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="produit_show")
     * @Method("GET")
     */
    public function showAction(Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);

        return $this->render('produit/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/{slug}/edit", name="produit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Produit $produit, GestionProduit $gestionProduit)
    {
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('AppBundle:Produit')->findAll();

        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('AppBundle\Form\ProduitType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $ancienneCategorie = $request->get('oldCategorie');
            if ($ancienneCategorie != $produit->getCategorie()){
                if (!$produit->getVente()){
                    $code = $gestionProduit->codeProduit($produit->getCategorie()->getId());
                    $produit->setReference($code);
                }else{
                    $this->addFlash('error', "Attention le produit a déjà été vendu donc vous ne pouvez plus changer sa catégorie");
                    return $this->redirectToRoute('produit_edit', ['slug'=>$produit->getSlug()]);
                }
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/edit.html.twig', array(
            'produit' => $produit,
            'produits' => $produits,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a produit entity.
     *
     * @Route("/{id}", name="produit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Produit $produit, GestionProduit $gestionProduit)
    {
        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $gestionProduit->deleteProduit($produit->getCategorie()->getId());
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('produit_index');
    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
