<?php

namespace AppBundle\Repository;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Liste par ordre alphabeitique
     */
    public function liste()
    {
        return $this->createQueryBuilder('c')->orderBy('c.libelle','ASC');
    }
    /**
     * Nombre de categorie
     */
    public function getNombre()
    {
        return $this->createQueryBuilder('c')->select('count(c.id)')->getQuery()->getSingleScalarResult();
    }

    /**
     * Calcul du nombre de categorie concerne par le domaine
     */
    public function getNombreByDomaine($domaine)
    {
        return $this->createQueryBuilder('c')
                    ->select('count(c.id)')
                    ->where('c.domaine = :id')
                    ->setParameter('id', $domaine)
                    ->getQuery()->getSingleScalarResult()
            ;
    }
}