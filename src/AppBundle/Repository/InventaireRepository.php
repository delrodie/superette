<?php

namespace AppBundle\Repository;

/**
 * InventaireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InventaireRepository extends \Doctrine\ORM\EntityRepository
{
    public function findInventaire($id)
    {
        return $this->createQueryBuilder('i')->where('i.id = :id')->setParameter('id', $id);
    }

    public function findDesc()
    {
        return $this->createQueryBuilder('i')->orderBy('i.id', 'DESC')->getQuery()->getResult();
    }
}
