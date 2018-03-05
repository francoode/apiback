<?php

namespace AppBundle\Repository;

use ApiBundle\ApiBundle;
use ApiBundle\Entity\User;
use AppBundle\AppBundle;

/**
 * UserExtendsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserExtendsRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUserStudy($user)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();
        $query->select('IDENTITY(ue.idStudy)')
            ->from('AppBundle:UserExtends', 'ue')
            ->innerJoin('ue.idUser','u')
            ->innerJoin('ue.idStudy','s')
            ->where('u.id = :idUser')
            ->setParameter('idUser', $user);

        return $query->getQuery()->getSingleScalarResult();


    }

}