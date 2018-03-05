<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Controller\IAppController;
use AppBundle\Controller\IOnboardingController;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\ORM\EntityManagerInterface;
use ApiSecurityBundle\Security\ApiKeyUserProvider;

class OnboardingSubscriber implements EventSubscriberInterface
{
    private $em;
    private $userProvider;

    public function __construct(
        EntityManagerInterface $entityManager,
        ApiKeyUserProvider $userProvider        
    )
    {
        $this->em = $entityManager;
        $this->userProvider = $userProvider;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        // get user
        $user = $this->userProvider->getUserForApiKey(
            $event->getRequest()->headers->get('x-bikip-apikey')
        );

        if ($controller[0] instanceof IAppController) {
            // get user extends
            $userExtends = $this
                ->em
                ->getRepository('AppBundle:UserExtends')
                ->findOneBy(array('idUser' => $user->getId()))
            ;

            if (null == $userExtends ||
                null == $userExtends->getIdStudy()) {
                throw new PreconditionFailedHttpException('Onboarding process not ran');
            }
        }

        if ($controller[0] instanceof IOnboardingController) {
            // get user extends
            $userExtends = $this
                ->em
                ->getRepository('AppBundle:UserExtends')
                ->findOneBy(array('idUser' => $user->getId()))
            ;

            if (null != $userExtends &&
                null != $userExtends->getIdStudy()) {
                throw new NotFoundHttpException();
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}