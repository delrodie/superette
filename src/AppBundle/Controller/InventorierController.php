<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Inventaire;
use AppBundle\Entity\Inventorier;
use AppBundle\Utils\GestionInventaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Inventorier controller.
 *
 * @Route("inventorier")
 */
class InventorierController extends Controller
{
    /**
     * Lists all inventorier entities.
     *
     * @Route("/{inventaire}", name="inventorier_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, $inventaire, GestionInventaire $gestionInventaire)
    {
        $em = $this->getDoctrine()->getManager();

        $inventoriers = $em->getRepository('AppBundle:Inventorier')->findBy(['inventaire'=>$inventaire],['id'=>"DESC"]);
        $inventorier = new Inventorier();
        $form = $this->createForm('AppBundle\Form\InventorierType', $inventorier, ['inventaire'=>$inventaire]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // verifcation  du montant a deuire
            $deduction = $em->getRepository("AppBundle:Inventaire")->findOneBy(['id'=>$inventaire])->getDeduction();
            if ($deduction < $inventorier->getMontant()) {
                $this->addFlash('error',"Echec: le montant ".$inventorier->getMontant()." enregistré est superieur au restant de la facture du fournisseur");
                return $this->redirectToRoute('inventorier_index',['inventaire'=>$inventaire]);
            }
            $quantite = $inventorier->getQuantite();
            $em->persist($inventorier);
            $em->flush();

            // Mise a jour du la table inventaire
            $gestionInventaire->deduction($inventaire, $inventorier->getMontant());
            $gestionInventaire->addStock($inventorier->getProduit()->getId(), $inventorier->getQuantite());
            $gestionInventaire->addNombreProduit($inventaire);
            //Mise a jour du prix d'achat du produit
            $gestionInventaire->updatePrixAchat($inventorier->getProduit()->getId(),$inventorier->getMontant(),$quantite);

            $montantrestant = $em->getRepository("AppBundle:Inventaire")->findOneBy(['id'=>$inventaire])->getDeduction();
            $this->addFlash('notice', "Produit enregistré avec succes. Montant restant <strong>".$montantrestant." </strong>");

            // Si montant restant est 0 alors renvoi liste des inventaires
            if ($montantrestant == 0) {
                $this->addFlash('notice',"BRAVO! l'enregistrement de la facture du fournisseur est effectif!");
                return $this->redirectToRoute('inventaire_index');
            }
            return $this->redirectToRoute('inventorier_index',['inventaire'=>$inventaire]);
        }

        return $this->render('inventorier/index.html.twig', array(
            'inventoriers' => $inventoriers,
            'inventorier' => $inventorier,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new inventorier entity.
     *
     * @Route("/new", name="inventorier_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $inventorier = new Inventorier();
        $form = $this->createForm('AppBundle\Form\InventorierType', $inventorier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inventorier);
            $em->flush();

            return $this->redirectToRoute('inventorier_show', array('id' => $inventorier->getId()));
        }

        return $this->render('inventorier/new.html.twig', array(
            'inventorier' => $inventorier,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a inventorier entity.
     *
     * @Route("/{id}/", name="inventorier_show")
     * @Method("GET")
     */
    public function showAction(Inventaire $inventaire)
    {
        $em =$this->getDoctrine()->getManager();
        $inventoriers = $em->getRepository("AppBundle:Inventorier")->findBy(['inventaire'=>$inventaire->getId()]);

        return $this->render('inventorier/show.html.twig', array(
            'inventoriers' => $inventoriers,
            'inventaire' => $inventaire,
        ));
    }

    /**
     * Displays a form to edit an existing inventorier entity.
     *
     * @Route("/{id}/edit", name="inventorier_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Inventorier $inventorier, GestionInventaire $gestionInventaire)
    {
        $em = $this->getDoctrine()->getManager();

        $inventaire = $em->getRepository("AppBundle:Inventaire")->findOneBy(['id'=>$inventorier->getInventaire()->getId()]);
        $deleteForm = $this->createDeleteForm($inventorier);
        $editForm = $this->createForm('AppBundle\Form\InventorierType', $inventorier, ['inventaire'=>$inventaire->getId()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $old_produit = $request->get('old_produit');
            $old_quantite = $request->get('old_quantite');
            $old_montant = $request->get('old_montant');
            $this->getDoctrine()->getManager()->flush();

            // Si le produit a été modifié alors faire la mise a jour des stocks
            if ($inventorier->getProduit()->getId() != $old_produit){
                $gestionInventaire->addStock($inventorier->getProduit()->getId(), $inventorier->getQuantite());
                $gestionInventaire->deleteStock($old_produit,$old_quantite);
                $gestionInventaire->updatePrixAchat($inventorier->getProduit()->getId(),$inventorier->getMontant(),$inventorier->getQuantite());
                $gestionInventaire->updatePrixAchat($old_produit,$old_montant,$old_quantite);
            }else{
                $gestionInventaire->deleteStock($inventorier->getProduit()->getId(),$old_quantite);
                $gestionInventaire->addStock($inventorier->getProduit()->getId(),$inventorier->getQuantite());
                $gestionInventaire->updatePrixAchat($inventorier->getProduit()->getId(),$inventorier->getMontant(),$inventorier->getQuantite());
            }

            $this->addFlash('notice', "Mise a jour effective");
            return $this->redirectToRoute('inventorier_show', array('id' => $inventorier->getInventaire()->getId()));
        }

        $inventoriers = $em->getRepository('AppBundle:Inventorier')->findBy(['inventaire'=>$inventaire],['id'=>"DESC"]);

        return $this->render('inventorier/edit.html.twig', array(
            'inventoriers' => $inventoriers,
            'inventorier' => $inventorier,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a inventorier entity.
     *
     * @Route("/{id}", name="inventorier_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Inventorier $inventorier, GestionInventaire $gestionInventaire)
    {
        $form = $this->createDeleteForm($inventorier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $inventaire = $inventorier->getInventaire()->getId();
            $montant = $inventorier->getMontant();
            $produit = $inventorier->getProduit()->getId();
            $quantite = $inventorier->getQuantite();
            $em->remove($inventorier);
            $em->flush();

            $gestionInventaire->deleteDeduction($inventaire,$montant);
            $gestionInventaire->deleteStock($produit,$quantite);
            $gestionInventaire->updatePrixAchat($produit,$montant,$quantite);
            $gestionInventaire->deleteNombreProduit($inventaire);

            $this->addFlash('notice', "Produit supprimer avec succès de cet approvisionnement");
        }

        return $this->redirectToRoute('inventorier_index',['inventaire'=>$inventaire]);
    }

    /**
     * Creates a form to delete a inventorier entity.
     *
     * @param Inventorier $inventorier The inventorier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Inventorier $inventorier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inventorier_delete', array('id' => $inventorier->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
