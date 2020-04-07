<?php

namespace AppBundle\Repository;

/**
 * FactureRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FactureRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNombreFactrure()
    {
        return $this->createQueryBuilder('f')
                    ->select('count(f.id)')
                    ->getQuery()->getSingleScalarResult()
            ;
    }

    public function findFacture($reference)
    {
        return $this->createQueryBuilder('f')
                    ->where('f.id = :reference')
                    ->setParameter('reference', $reference)
            ;
    }

    public function findByPeriode($debut=null, $fin=null,$caissier=null)
    {
        if ($debut && $fin && $caissier){
            return $this->createQueryBuilder('f')
                        ->where('f.date >= :debut')
                        ->andWhere('f.date <= :fin')
                        ->andWhere('f.montant <> 0')
                        ->andWhere('f.statut IS NULL')
                        ->andWhere('f.publiePar = :caissier')
                        ->orderBy('f.publieLe', 'DESC')
                        ->setParameters([
                            'debut' => $debut,
                            'fin' => $fin,
                            'caissier' => $caissier
                        ])
                        ->getQuery()->getResult()
                ;
        }elseif ($debut && $fin){
            return $this->createQueryBuilder('f')
                ->where('f.date >= :debut')
                ->andWhere('f.date <= :fin')
                ->andWhere('f.montant <> 0')
                ->andWhere('f.statut IS NULL')
                ->orderBy('f.publieLe', 'DESC')
                ->setParameters([
                    'debut' => $debut,
                    'fin' => $fin,
                ])
                ->getQuery()->getResult()
                ;
        }elseif ($debut && $caissier){
            return $this->createQueryBuilder('f')
                        ->where('f.date >= :debut')
                        ->andWhere('f.montant <> 0')
                        ->andWhere('f.statut IS NULL')
                        ->andWhere('f.publiePar = :caissier')
                        ->orderBy('f.publieLe', 'DESC')
                        ->setParameters([
                            'debut'=> $debut,
                            'caissier'=>$caissier
                        ])
                        ->getQuery()->getResult()
                ;
        }elseif ($fin && $caissier){
            return $this->createQueryBuilder('f')
                        ->where('f.date <= :fin')
                        ->andWhere('f.montant <> 0')
                        ->andWhere('f.statut IS NULL')
                        ->andWhere('f.publiePar = :caissier')
                        ->orderBy('f.publieLe', 'DESC')
                        ->setParameters([
                            'fin'=> $fin,
                            'caissier' => $caissier,
                        ])
                        ->getQuery()->getResult()
                ;
        }elseif ($debut){
            return $this->createQueryBuilder('f')
                ->where('f.date >= :debut')
                ->andWhere('f.montant <> 0')
                ->andWhere('f.statut IS NULL')
                ->orderBy('f.publieLe', 'DESC')
                ->setParameter('debut', $debut)
                ->getQuery()->getResult()
                ;
        }elseif ($fin){
            return $this->createQueryBuilder('f')
                ->where('f.date <= :fin')
                ->andWhere('f.montant <> 0')
                ->andWhere('f.statut IS NULL')
                ->orderBy('f.publieLe', 'DESC')
                ->setParameter('fin', $fin)
                ->getQuery()->getResult()
                ;
        }elseif ($caissier){
            return $this->createQueryBuilder('f')
                ->where('f.publiePar = :caissier')
                ->andWhere('f.montant <> 0')
                ->andWhere('f.statut IS NULL')
                ->orderBy('f.publieLe', 'DESC')
                ->setParameter('caissier', $caissier)
                ->getQuery()->getResult()
                ;
        }else{
            return $this->createQueryBuilder('f')->where('f.montant <> 0')
                ->andWhere('f.statut IS NULL')->orderBy('f.publieLe','DESC')->getQuery()->getResult();
        }
    }

    public function findListeDESC()
    {
        return $this->createQueryBuilder('f')
                    ->andWhere('f.montant <> 0')
                    ->andWhere('f.statut IS NULL')
                    //->groupBy('f.date')
                    ->orderBy('f.publieLe', 'DESC')
                    ->getQuery()->getResult()
            ;
    }

    /**
     * recette correspondante à la date selectionnée
     *
     * @param $date
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRecette($date)
    {
        return $this->createQueryBuilder('f')
                    ->select('sum(f.montant)')
                    ->where('f.statut IS NULL')
                    ->andWhere('f.date = :date')
                    ->setParameter('date', $date)
                    ->getQuery()->getSingleScalarResult();

    }

    public function getNombreproduit($date)
    {
        return $this->createQueryBuilder('f')
                    ->select('sum(f.nombreProduit)')
                    ->where('f.statut IS NULL')
                    ->andWhere('f.date = :date')
                    ->setParameter('date', $date)
                    ->getQuery()->getSingleScalarResult();
            ;
    }
}
