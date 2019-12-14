<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vente;
use AppBundle\Utils\GestionFacture;
use AppBundle\Utils\GestionInventaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Vente controller.
 *
 * @Route("vente")
 */
class VenteController extends Controller
{
    /**
     * Lists all vente entities.
     *
     * @Route("/", name="vente_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ventes = $em->getRepository('AppBundle:Vente')->findAll();

        return $this->render('vente/index.html.twig', array(
            'ventes' => $ventes,
        ));
    }

    /**
     * Creates a new vente entity.
     *
     * @Route("/new/{facture}", name="vente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $facture, GestionFacture $gestionFacture, GestionInventaire $gestionInventaire)
    {
        $em = $this->getDoctrine()->getManager();
        $vente = new Vente();
        $form = $this->createForm('AppBundle\Form\VenteType', $vente,['facture'=>$facture]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //recherche du produit concerne
            $produitReference = $request->get('produit');
            $produit = $em->getRepository("AppBundle:Produit")->findByReferenceOrCode($produitReference); //dump($produit);die();
            if (!$produit){
                $this->addFlash('error', "Attention ce produit n'existe pas");
                return $this->redirectToRoute('vente_new',['facture'=>$facture]);
            }
            if (!$vente->getQuantite()){
                $qte = 1;
            }else{
                $qte = $vente->getQuantite();
            }
            $montant = $qte * $produit->getPrixVente();
            // Verification de non existence du produit dans la facture encore
            $factureEntity = $em->getRepository("AppBundle:Facture")->findOneBy(['id'=>$facture]);
            $venteExist = $em->getRepository("AppBundle:Vente")->findOneBy(['produit'=>$produit->getId(), 'facture'=>$facture]);
            if ($venteExist){
                $venteExist->setMontant($venteExist->getMontant()+$montant);
                $venteExist->setQuantite($venteExist->getQuantite()+$qte);
                $em->flush($venteExist);
                $gestionFacture->addVente($facture,$montant);
            }else{
                $vente->setProduit($produit);
                $vente->setQuantite($qte);
                $vente->setMontant($montant);
                $vente->setFacture($factureEntity); //dump($vente);die();
                $em->persist($vente);
                $em->flush();
                // Mise a jour de la table facture
                $gestionFacture->addVente($facture,$montant,true);
            }

            $gestionInventaire->deleteStock($produit->getId(),$qte);
            return $this->redirectToRoute('vente_new', array('facture' => $facture));
        }
        $ventes = $em->getRepository("AppBundle:Vente")->findBy(['facture'=>$facture]);
        $factures = $em->getRepository("AppBundle:Facture")->findOneBy(['id'=>$facture]);
        return $this->render('vente/new.html.twig', array(
            'ventes' => $ventes,
            'vente' => $vente,
            'form' => $form->createView(),
            'facture' => $factures
        ));
    }

    /**
     * Finds and displays a vente entity.
     *
     * @Route("/{id}", name="vente_show")
     * @Method("GET")
     */
    public function showAction(Vente $vente)
    {
        $deleteForm = $this->createDeleteForm($vente);

        return $this->render('vente/show.html.twig', array(
            'vente' => $vente,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing vente entity.
     *
     * @Route("/{id}/edit", name="vente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Vente $vente)
    {
        $deleteForm = $this->createDeleteForm($vente);
        $editForm = $this->createForm('AppBundle\Form\VenteType', $vente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vente_edit', array('id' => $vente->getId()));
        }

        return $this->render('vente/edit.html.twig', array(
            'vente' => $vente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a vente entity.
     *
     * @Route("/{id}", name="vente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Vente $vente)
    {
        $form = $this->createDeleteForm($vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vente);
            $em->flush();
        }

        return $this->redirectToRoute('vente_index');
    }

    /**
     * Creates a form to delete a vente entity.
     *
     * @param Vente $vente The vente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vente $vente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vente_delete', array('id' => $vente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
