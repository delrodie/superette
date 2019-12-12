<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fournisseur;
use AppBundle\Utils\GestionFournisseur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Fournisseur controller.
 *
 * @Route("fournisseur")
 */
class FournisseurController extends Controller
{
    /**
     * Lists all fournisseur entities.
     *
     * @Route("/", name="fournisseur_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, GestionFournisseur $gestionFournisseur)
    {
        $em = $this->getDoctrine()->getManager();

        $fournisseurs = $em->getRepository('AppBundle:Fournisseur')->findAll();
        $fournisseur = new Fournisseur();
        $form = $this->createForm('AppBundle\Form\FournisseurType', $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $fournisseur->setCode($gestionFournisseur->code());
            $em->persist($fournisseur);
            $em->flush();

            return $this->redirectToRoute('fournisseur_index');
        }

        return $this->render('fournisseur/index.html.twig', array(
            'fournisseurs' => $fournisseurs,
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new fournisseur entity.
     *
     * @Route("/new", name="fournisseur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm('AppBundle\Form\FournisseurType', $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fournisseur);
            $em->flush();

            return $this->redirectToRoute('fournisseur_show', array('id' => $fournisseur->getId()));
        }

        return $this->render('fournisseur/new.html.twig', array(
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a fournisseur entity.
     *
     * @Route("/{slug}", name="fournisseur_show")
     * @Method("GET")
     */
    public function showAction(Fournisseur $fournisseur)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($fournisseur);
        $fournisseurs = $em->getRepository("AppBundle:Fournisseur")->findAll();
        $inventaires = $em->getRepository("AppBundle:Inventaire")->findBy(['fournisseur'=>$fournisseur->getId()],['date'=>'DESC']);
        //dump($inventaires);die();

        return $this->render('fournisseur/show.html.twig', array(
            'fournisseur' => $fournisseur,
            'fournisseurs' => $fournisseurs,
            'delete_form' => $deleteForm->createView(),
            'inventaires' => $inventaires,
        ));
    }

    /**
     * Displays a form to edit an existing fournisseur entity.
     *
     * @Route("/{slug}/edit", name="fournisseur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Fournisseur $fournisseur)
    {
        $deleteForm = $this->createDeleteForm($fournisseur);
        $editForm = $this->createForm('AppBundle\Form\FournisseurType', $fournisseur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fournisseur_show', array('slug' => $fournisseur->getSlug()));
        }
        $em = $this->getDoctrine()->getManager();
        $fournisseurs = $em->getRepository("AppBundle:Fournisseur")->findAll();

        return $this->render('fournisseur/edit.html.twig', array(
            'fournisseur' => $fournisseur,
            'fournisseurs' => $fournisseurs,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a fournisseur entity.
     *
     * @Route("/{id}", name="fournisseur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Fournisseur $fournisseur)
    {
        $form = $this->createDeleteForm($fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fournisseur);
            $em->flush();
        }

        return $this->redirectToRoute('fournisseur_index');
    }

    /**
     * Creates a form to delete a fournisseur entity.
     *
     * @param Fournisseur $fournisseur The fournisseur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Fournisseur $fournisseur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fournisseur_delete', array('id' => $fournisseur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
