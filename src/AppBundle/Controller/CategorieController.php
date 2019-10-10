<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Utils\GestionProduit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Categorie controller.
 *
 * @Route("categorie")
 */
class CategorieController extends Controller
{
    /**
     * Lists all categorie entities.
     *
     * @Route("/", name="categorie_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, GestionProduit $gestionProduit)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Categorie')->liste()->getQuery()->getResult();
        $categorie = new Categorie();
        $form = $this->createForm('AppBundle\Form\CategorieType', $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $code = $gestionProduit->codeCategorie($categorie->getDomaine()->getId());
            $categorie->setCode($code);
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('notice', "La catégorie <strong>".$categorie->getLibelle()."</strong> a été ajoutée avec succès!");

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/index.html.twig', array(
            'categories' => $categories,
            'categorie' => $categorie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new categorie entity.
     *
     * @Route("/new", name="categorie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm('AppBundle\Form\CategorieType', $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('categorie_show', array('id' => $categorie->getId()));
        }

        return $this->render('categorie/new.html.twig', array(
            'categorie' => $categorie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a categorie entity.
     *
     * @Route("/{id}", name="categorie_show")
     * @Method("GET")
     */
    public function showAction(Categorie $categorie)
    {
        $deleteForm = $this->createDeleteForm($categorie);

        return $this->render('categorie/show.html.twig', array(
            'categorie' => $categorie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     * @Route("/{slug}/edit", name="categorie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Categorie $categorie, GestionProduit $gestionProduit)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Categorie')->liste()->getQuery()->getResult();

        $deleteForm = $this->createDeleteForm($categorie);
        $editForm = $this->createForm('AppBundle\Form\CategorieType', $categorie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $ancienDomaine = $request->get('oldDomaine'); //dump($ancienDomaine);die();
            $nouveauDomaine = $categorie->getDomaine();
            if ($ancienDomaine != $nouveauDomaine){
                // si la catégorie a deja des produits alors annuler le changement de domaine
                $nombreProduit = $em->getRepository("AppBundle:Categorie")->findOneBy(['id'=>$categorie->getId()])->getNombreProduit();
                if ($nombreProduit){
                    $this->addFlash('error', "Echec : cette catégorie est déja associée à des produits donc vous ne pouvez plus changer le domaine");
                    return $this->redirectToRoute('categorie_edit',['slug'=>$categorie->getSlug()]);
                }

                $code = $gestionProduit->codeCategorie($nouveauDomaine);
                $categorie->setCode($code);
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "La catégorie <strong>".$categorie->getLibelle()."</strong> a été modifiée avec succès!");
            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/edit.html.twig', array(
            'categorie' => $categorie,
            'categories' => $categories,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a categorie entity.
     *
     * @Route("/{id}", name="categorie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Categorie $categorie)
    {
        $form = $this->createDeleteForm($categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }

    /**
     * Creates a form to delete a categorie entity.
     *
     * @param Categorie $categorie The categorie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categorie $categorie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categorie_delete', array('id' => $categorie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
