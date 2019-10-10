<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Domaine;
use AppBundle\Utils\GestionDomaine;
use AppBundle\Utils\GestionProduit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Domaine controller.
 *
 * @Route("domaine")
 */
class DomaineController extends Controller
{
    /**
     * Lists all domaine entities.
     *
     * @Route("/", name="domaine_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, GestionProduit $gestionProduit)
    {
        $em = $this->getDoctrine()->getManager();

        $domaines = $em->getRepository('AppBundle:Domaine')->findAll();
        $domaine = new Domaine();
        $form = $this->createForm('AppBundle\Form\DomaineType', $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $code = $gestionProduit->codeDomaine();
            $domaine->setCode($code);
            $em->persist($domaine);
            $em->flush();

            return $this->redirectToRoute('domaine_index');
        }

        return $this->render('domaine/index.html.twig', array(
            'domaines' => $domaines,
            'domaine' => $domaine,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new domaine entity.
     *
     * @Route("/new", name="domaine_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $domaine = new Domaine();
        $form = $this->createForm('AppBundle\Form\DomaineType', $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($domaine);
            $em->flush();

            return $this->redirectToRoute('domaine_show', array('id' => $domaine->getId()));
        }

        return $this->render('domaine/new.html.twig', array(
            'domaine' => $domaine,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a domaine entity.
     *
     * @Route("/{id}", name="domaine_show")
     * @Method("GET")
     */
    public function showAction(Domaine $domaine)
    {
        $deleteForm = $this->createDeleteForm($domaine);

        return $this->render('domaine/show.html.twig', array(
            'domaine' => $domaine,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing domaine entity.
     *
     * @Route("/{slug}/edit", name="domaine_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Domaine $domaine)
    {
        $em = $this->getDoctrine()->getManager();

        $domaines = $em->getRepository('AppBundle:Domaine')->findAll();
        $deleteForm = $this->createDeleteForm($domaine);
        $editForm = $this->createForm('AppBundle\Form\DomaineType', $domaine);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('domaine_index');
        }

        return $this->render('domaine/edit.html.twig', array(
            'domaine' => $domaine,
            'domaines' => $domaines,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a domaine entity.
     *
     * @Route("/{id}", name="domaine_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Domaine $domaine)
    {
        $form = $this->createDeleteForm($domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($domaine);
            $em->flush();
        }

        return $this->redirectToRoute('domaine_index');
    }

    /**
     * Creates a form to delete a domaine entity.
     *
     * @param Domaine $domaine The domaine entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Domaine $domaine)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('domaine_delete', array('id' => $domaine->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
