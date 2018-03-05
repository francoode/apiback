<?php
namespace AppBundle\Handler;
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 13:46
 */
use ApiBundle\Service\EntitiesService;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OnboardingHandler extends EntitiesService
{
    public function all()
    {
        $userExtends = $this
            ->em
            ->getRepository('AppBundle:UserExtends')
            ->findBy(array('idUser' => $this->helperService->requester->getId()))
        ;

        $entity = null;
        if (null != $userExtends) {
            $entity = $userExtends->getIdStudy();
        }

        return $entity;
    }
}