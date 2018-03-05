<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 24/01/18
 * Time: 10:50
 */

namespace AppBundle\Service;
use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\LogAction;
use AppBundle\Util\AutomaticLogMessagesConst;

///bikip_api.auxiliarFunctions.service
class AuxiliarFunctionsService extends EntitiesService
{
    public function addStudyToParameters($parameters)
    {
        $user  = $this->helperService->requester->getId();
        $parameters['study'] = $this->em->getRepository('AppBundle:UserExtends')->getUserStudy($user);

        return $parameters;
    }

    public function addStudyAndUserOwnerToParameters($parameters)
    {
        $user  = $this->helperService->requester->getId();
        $parameters['userOwner'] = $user;
        $parameters['study'] = $this->em->getRepository('AppBundle:UserExtends')->getUserStudy($user);

        return $parameters;
    }


    public function getStudyUser()
    {
        $user  = $this->helperService->requester->getId();
        $study = $this->em->getRepository('AppBundle:UserExtends')->getUserStudy($user);

        return $study;
    }




    public function defineServiceNote($origin)
    {
        if($origin == LogAction::contacts)
        {
            $serviceToCall = 'bikip_api.notecontact.handler';
        }
        else if($origin == LogAction::business)
        {
            $serviceToCall = 'bikip_api.notebusiness.handler';
        }
        else if($origin == LogAction::companies)
        {
            $serviceToCall = 'bikip_api.notecompany.handler';
        }
        else
        {
            $serviceToCall = null;
        }
        return $serviceToCall;
    }



}