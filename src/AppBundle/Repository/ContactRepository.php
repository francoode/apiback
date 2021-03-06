<?php

namespace AppBundle\Repository;

/**
 * ContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends \Doctrine\ORM\EntityRepository
{
    public function checkExistsContact($study, $emailContact)
    {
        $em = $this->getEntityManager();
        $query  = $em->createQueryBuilder();
        $query->select('COUNT(c)')
              ->from('AppBundle:Contact', 'c')
              ->where('c.email = :emailContact')
              ->andWhere('c.study = :idStudy')
              ->setParameter('emailContact', $emailContact)
              ->setParameter('idStudy', $study);

        return $query->getQuery()->getSingleScalarResult();

    }
}
