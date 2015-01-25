<?php

namespace AssoMaker\AnimBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AnimationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnimationRepository extends EntityRepository {

    public function findAllNonDeleted() {

        return $this->createQueryBuilder('a')
                        ->where('a.statut >=0')
                        ->orderBy('a.nom')
        ;
    }

    public function search($s) {
        return $this->getEntityManager()
                        ->createQuery("SELECT a FROM AssoMakerAnimBundle:Animation a WHERE (a.nom LIKE :s)")
                        ->setParameter('s', "%" . $s . "%")
                        ->getResult();
    }

}
