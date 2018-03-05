<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 29/01/18
 * Time: 12:24
 */

namespace AppBundle\Service;

use AppBundle\Util\AutomaticLogMessagesConst;
use ApiBundle\Service\EntitiesService;
use AppBundle\EventSubscriber\LogActionSuscriber;
use AppBundle\Entity\LogAction;


class AutomaticLogMessagesService extends EntitiesService
{
    public function generateMessage($args, $auxiliar)
    {
        $message = [];
        $message['description'] = $this->generateContent($args, $auxiliar);
        $message['createAt'] = $this->generateDateTime();
        $message['type'] = $this->getTypeEntity($auxiliar['EntityName']);
        $message['idType'] = $args->getObject()->getId();


        $this->container->get('bikip_api.logaction.handler')->post($message);
    }

    private function generateDateTime()
    {
        //Create and format to string DateTime
        $time = new \DateTime('America/Argentina/Buenos_Aires');
        $time = $time->format('Y-m-d H:i:s');

        return $time;
    }


    private function generateContent($arg, $auxiliar)
    {
        $message = $this->helperService->requester->getUsername();     //Usuario X
        $message .= $this->defineAction($auxiliar);                    //ha creado    / ha eliminado / ha modificado: nombre J a W, .. /
  //      $message .= $this->defineEntity($auxiliar['EntityName']);  //la compania  / el contacto / ..
  //      $message .= (method_exists($arg->getEntity(),'getName')) ? $arg->getEntity()->getName() : null; // Evil Corp   / Elliot Alderson


        return $message;
    }

    private function defineAction($auxiliar)
    {
       if($auxiliar['TypeEvent'] == AutomaticLogMessagesConst::type_create)
       {
           $msg = AutomaticLogMessagesConst::create;
       }
       elseif($auxiliar['TypeEvent'] == AutomaticLogMessagesConst::type_update)
       {
            $msg = AutomaticLogMessagesConst::update;
            $msg .= $this->fieldsUpdateChanges($auxiliar['ChangeSet']); //Create message with changed fields
       }
       elseif($auxiliar['TypeEvent'] == AutomaticLogMessagesConst::type_remove)
       {
           $msg = AutomaticLogMessagesConst::delete;
       }
       else
       {
           $msg = "";
       }
          return $msg;
    }

    private function fieldsUpdateChanges($changes) //ha cambiado nombre: A a X
    {
        $msg = "";
        $arrayConst = AutomaticLogMessagesConst::varTranslations;
        foreach ($changes as $key => $value)
        {

            $msg .= isset($arrayConst[$key]) ? $arrayConst[$key] : null;
            $msg .= AutomaticLogMessagesConst::whiteSpace;
            $msg .= $value[0];
            $msg .= AutomaticLogMessagesConst::a;
            $msg .= AutomaticLogMessagesConst::whiteSpace;
            $msg .= $value[1];
           // $msg .= AutomaticLogMessagesConst::whiteSpacecoma;

            //example: nombre Evil Company a Good Company, Cantidad 1000 a 50
        }
      //  $msg .= AutomaticLogMessagesConst::en;
        return $msg;
    }

    private function defineEntity($entity)
    {
        if($entity == 'AppBundle\Entity\Business')
        {
            $msg = AutomaticLogMessagesConst::el;
            $msg .=AutomaticLogMessagesConst::business;
        }
        elseif($entity == 'AppBundle\Entity\Contact')
        {
            $msg = AutomaticLogMessagesConst::el;
            $msg .=AutomaticLogMessagesConst::contact;
        }
        elseif($entity == 'AppBundle\Entity\Company')
        {
            $msg = AutomaticLogMessagesConst::la;
            $msg .=AutomaticLogMessagesConst::company;
        }
        elseif($entity == 'AppBundle\Entity\Note')
        {
            $msg = AutomaticLogMessagesConst::una;
            $msg .= AutomaticLogMessagesConst::note;
        }
        elseif($entity == 'AppBundle\Entity\Task')
        {
            $msg = AutomaticLogMessagesConst::una;
            $msg .= AutomaticLogMessagesConst::task;
        }
        else{
            $msg = '';
        }

        return $msg;
    }

    private function getTypeEntity($entityName)
    {
        $pos = strripos($entityName, '\\');
        $type = substr($entityName, $pos+1);

        return $type;
    }


}