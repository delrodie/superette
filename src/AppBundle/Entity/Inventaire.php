<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Inventaire
 *
 * @ORM\Table(name="inventaire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InventaireRepository")
 */
class Inventaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_fournisseur", type="string", length=255, nullable=true)
     */
    private $refFournisseur;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer", nullable=true)
     */
    private $montant;

    /**
     * @var int
     *
     * @ORM\Column(name="deduction", type="integer", nullable=true)
     */
    private $deduction;

    /**
     * @var int
     *
     * @ORM\Column(name="nombreProduit", type="integer", nullable=true)
     */
    private $nombreProduit;

    /**
     * @var bool
     *
     * @ORM\Column(name="flag", type="boolean", nullable=true)
     */
    private $flag;

    /**
     * @var bool
     *
     * @ORM\Column(name="statut", type="boolean", nullable=true)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Fournisseur")
     * @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id")
     */
    private $fournisseur;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="publie_par", type="string", length=25, nullable=true)
     */
    private $publiePar;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="modifie_par", type="string", length=25, nullable=true)
     */
    private $modifiePar;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="publie_le", type="datetimetz", nullable=true)
     */
    private $publieLe;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="modifie_le", type="datetimetz", nullable=true)
     */
    private $modifieLe;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Inventaire
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return Inventaire
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set deduction
     *
     * @param integer $deduction
     *
     * @return Inventaire
     */
    public function setDeduction($deduction)
    {
        $this->deduction = $deduction;

        return $this;
    }

    /**
     * Get deduction
     *
     * @return int
     */
    public function getDeduction()
    {
        return $this->deduction;
    }

    /**
     * Set nombreProduit
     *
     * @param integer $nombreProduit
     *
     * @return Inventaire
     */
    public function setNombreProduit($nombreProduit)
    {
        $this->nombreProduit = $nombreProduit;

        return $this;
    }

    /**
     * Get nombreProduit
     *
     * @return int
     */
    public function getNombreProduit()
    {
        return $this->nombreProduit;
    }

    /**
     * Set flag
     *
     * @param boolean $flag
     *
     * @return Inventaire
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return bool
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set publiePar
     *
     * @param string $publiePar
     *
     * @return Inventaire
     */
    public function setPubliePar($publiePar)
    {
        $this->publiePar = $publiePar;

        return $this;
    }

    /**
     * Get publiePar
     *
     * @return string
     */
    public function getPubliePar()
    {
        return $this->publiePar;
    }

    /**
     * Set modifiePar
     *
     * @param string $modifiePar
     *
     * @return Inventaire
     */
    public function setModifiePar($modifiePar)
    {
        $this->modifiePar = $modifiePar;

        return $this;
    }

    /**
     * Get modifiePar
     *
     * @return string
     */
    public function getModifiePar()
    {
        return $this->modifiePar;
    }

    /**
     * Set publieLe
     *
     * @param \DateTime $publieLe
     *
     * @return Inventaire
     */
    public function setPublieLe($publieLe)
    {
        $this->publieLe = $publieLe;

        return $this;
    }

    /**
     * Get publieLe
     *
     * @return \DateTime
     */
    public function getPublieLe()
    {
        return $this->publieLe;
    }

    /**
     * Set modifieLe
     *
     * @param \DateTime $modifieLe
     *
     * @return Inventaire
     */
    public function setModifieLe($modifieLe)
    {
        $this->modifieLe = $modifieLe;

        return $this;
    }

    /**
     * Get modifieLe
     *
     * @return \DateTime
     */
    public function getModifieLe()
    {
        return $this->modifieLe;
    }

    /**
     * Set fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     *
     * @return Inventaire
     */
    public function setFournisseur(\AppBundle\Entity\Fournisseur $fournisseur = null)
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * Get fournisseur
     *
     * @return \AppBundle\Entity\Fournisseur
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Inventaire
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set refFournisseur
     *
     * @param string $refFournisseur
     *
     * @return Inventaire
     */
    public function setRefFournisseur($refFournisseur)
    {
        $this->refFournisseur = $refFournisseur;

        return $this;
    }

    /**
     * Get refFournisseur
     *
     * @return string
     */
    public function getRefFournisseur()
    {
        return $this->refFournisseur;
    }

    /**
     * Set statut
     *
     * @param boolean $statut
     *
     * @return Inventaire
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return boolean
     */
    public function getStatut()
    {
        return $this->statut;
    }
}
