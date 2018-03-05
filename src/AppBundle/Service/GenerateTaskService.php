<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 15/02/18
 * Time: 11:31
 */

namespace AppBundle\Service;

use ApiBundle\Service\EntitiesService;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder as JMSBuilder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use AppBundle\Entity\Task;
use Symfony\Component\HttpFoundation\Response;


class GenerateTaskService extends EntitiesService
{
    public function generateTask($request)
    {
        $recurrence = $request->get('recurrence');
        $originalTask = $request->get('task');

        if($originalTask['isRecurrent'])
        {
            $recurreceEntity = $this->container->get('bikip.api.recurrence.handler')->post($recurrence);
            $originalTask['recurrence'] = $recurreceEntity['message']->getId();
        }

        $taskReturn = $this->container->get('bikip_api.task.handler')->post($originalTask);

        if(!$originalTask['isRecurrent'])
        {
            return $taskReturn;
        }

        $parentTask = $taskReturn['message'];

        $entityClonedArray = $this->cloneTask($parentTask, $request->get('recurrence'));
        $entityClonedArray[] = $parentTask;

        $returnMessaage['message'] = $entityClonedArray;
        $returnMessaage['status'] = Response::HTTP_CREATED;

        return $returnMessaage;

    }

    public function cloneTask($parentTask, $recurrence)
    {
        //$serializer = JMSBuilder::create()->build();

        if(!is_null($parentTask->getEndDate()))
        {
            $intervalBetweenStartEnd = $parentTask->getStartDate()->diff($parentTask->getEndDate());
        }

        $date = $this->dateIntervalAdd($parentTask->getStartDate(), $recurrence);

        $endDateTotal = new \DateTime($recurrence['endDateRecurrent']);
        $endDateTotal->setTime(23,59,59);

        while ($date <= $endDateTotal)
        {
            $newTask = clone $parentTask;
            $newTask->setParent($parentTask);
            $dateNew = clone $date;
            $newTask->setStartDate($dateNew);

            if(isset($intervalBetweenStartEnd))
            {
               $auxStartDate = clone $newTask->getStartDate();
                $auxStartDate->add($intervalBetweenStartEnd);
                $newTask->setEndDate($auxStartDate);
            }

            //$newTask = $serializer->toArray($newTask);
            //unset($newTask['id']);

            $newEnityCloned = $this->container->get('bikip_api.task.handler')->postCloneEntity($newTask);
            $entityClonedArray[] = $newEnityCloned['message'];
            $date = $this->dateIntervalAdd($date, $recurrence);
        }

        $this->em->flush();

        return $entityClonedArray;

    }




    public function dateIntervalAdd($preDate, $recurrence)
    {
        if($recurrence['typeRecurrence'] == 'day')
        {
            $interval = new \DateInterval('P'.$recurrence['separation'].'D');
            $newDate = $preDate->add($interval);
        }
        elseif($recurrence['typeRecurrence'] == 'mounth')
        {
            $interval = new \DateInterval('P'.$recurrence['separation'].'M');
            $newDate = $preDate->add($interval);
            if(!is_null($recurrence['dayOfMonth']))
            {
                $newDate->setDate($newDate->format('Y'),$newDate->format('m'),$recurrence['dayOfMonth']);
            }
        }
        elseif($recurrence['typeRecurrence'] == 'year')
        {
            $interval = new \DateInterval('P'.$recurrence['separation'].'Y');
            $newDate = $preDate->add($interval);
            if(!is_null($recurrence['mountOfYear']))
            {
                $newDate->setDate($newDate->format('Y'),$recurrence['mounthOfYear'],$newDate->format('d'));
            }
            if(!is_null($recurrence['dayOfMonth']))
            {
                $newDate->setDate($newDate->format('Y'),$newDate->format('m'),$recurrence['dayOfMonth']);
            }
        }
        else
        {
            $newDate = $preDate;
        }

        return $newDate;
    }
}