<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Liste des utilisateurs excepté 'delrodie
     */
    public function findUsers()
    {
        return $this->createQueryBuilder('u')
                    ->where('u.username <> :user')
                    ->orderBy('u.username', 'ASC')
                    ->setParameter('user', 'delrodie')
                    ->getQuery()->getResult()
            ;
    }
}
