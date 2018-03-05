<?php

namespace ApiBundle\Service;


use ApiBundle\Entity\User;
use ApiSecurityBundle\Security\ApiKeyUserProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

abstract class EntitiesService
{
    /** @var User */
    protected $requester;
    protected $requesterIp;
    /** @var Container */
    protected $container;
    /** @var EntityManager */
    protected $em;
    /** @var HelperService */
    protected $helperService;
    /** @var ApiKeyUserProvider */
    private $userProvider;
    /** @var FormFactory */
    protected $formFactory;

    protected $registerConfirmationRequired;

    protected $tokenGenerator;

    public function setTokenGenerator($tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    public function setRegisterConfirmationRequired($registerConfirmationRequired)
    {
        $this->registerConfirmationRequired = $registerConfirmationRequired;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setApiKeyUserProvider(ApiKeyUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function setHelperService(HelperService $helperService)
    {
        $this->helperService = $helperService;
    }

    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->requester = $this->userProvider->getUserForApiKey(
            $event->getRequest()->headers->get('x-kodear-apikey')
        );
        $this->requesterIp = $event->getRequest()->getClientIp();
    }

    public function getRequester()
    {
        return $this->requester;
    }
}
