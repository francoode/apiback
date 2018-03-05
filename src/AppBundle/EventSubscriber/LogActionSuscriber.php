<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 29/01/18
 * Time: 10:01
 */

namespace AppBundle\EventSubscriber;

use AppBundle\AppBundle;
use AppBundle\Entity\Business;
use AppBundle\Entity\Company;
use AppBundle\Entity\Contact;
use AppBundle\Entity\LogAction;
use AppBundle\Entity\LogActionBusiness;
use AppBundle\Entity\Note;
use AppBundle\Entity\Task;
use AppBundle\Util\AutomaticLogMessagesConst;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Mapping\PostUpdate;


class LogActionSuscriber implements EventSubscriber
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'postRemove'
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        if(!$this->isAnEntityAccepted($args->getEntity(), "Create"))
        {
            return;
        }
        $auxiliar['TypeEvent'] = AutomaticLogMessagesConst::type_create;

        $this->index($args, $auxiliar);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        if(!$this->isAnEntityAccepted($args->getEntity(), "Update"))
        {
            return;
        }

        $auxiliar['TypeEvent'] = AutomaticLogMessagesConst::type_update;
        $auxiliar['ChangeSet'] = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($args->getEntity());

        $this->index($args, $auxiliar);

    }

    public function postRemove(LifecycleEventArgs $args)
    {
        if(!$this->isAnEntityAccepted($args->getEntity()))
        {
            return;
        }

        $auxiliar['TypeEvent'] = AutomaticLogMessagesConst::type_remove;
        $this->index($args, $auxiliar);

    }


    private function index($args, $auxiliar)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $entityName = $em->getMetadataFactory()->getMetadataFor(get_class($args->getEntity()))->getName();
        $auxiliar['EntityName'] = $entityName;


        $this->container->get('bikip_api.generateAutomaticLogMessages')->generateMessage($args, $auxiliar);
    }

    private function isAnEntityAccepted($entity, $type=null)
    {
        if($entity instanceof Business)
        {
            return true;
        }
        elseif ($entity instanceof Contact)
        {
            return true;
        }
        elseif ($entity instanceof Company) {
            return true;
        }
        elseif ($entity instanceof Note)
        {
            return true;
        }
        elseif($entity instanceof Task)
        {
            if($type == "Create")
            {
                return $response = (is_null($entity->getParent()))? true : false;       //Evitar crear multiples logs para Task recurrentes
            }
            else
            {
                return  true;
            }
        }
        else
        {
            return false;
        }
    }

}