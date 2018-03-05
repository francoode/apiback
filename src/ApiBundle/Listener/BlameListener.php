<?php

namespace ApiBundle\Listener;

use ApiSecurityBundle\Security\ApiKeyUserProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Gedmo\Blameable\BlameableListener;

/**
 * BlameListener
 *
 */
class BlameListener implements EventSubscriberInterface
{
    private $authorizationChecker;
    private $tokenStorage;
    private $blameableListener;
    private $userProvider;

    public function __construct(BlameableListener $blameableListener, $tokenStorage = null, AuthorizationCheckerInterface $authorizationChecker = null, ApiKeyUserProvider $apiKeyUserProvider)
    {
        $this->blameableListener = $blameableListener;
        $this->userProvider = $apiKeyUserProvider;

        if (null !== $tokenStorage && !$tokenStorage instanceof TokenStorageInterface) {
            throw new \InvalidArgumentException(sprintf('The second argument of the %s constructor should be a Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface or a Symfony\Component\Security\Core\SecurityContextInterface or null.', __CLASS__));
        }
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Set the username from the security context by listening on core.request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if (null === $this->tokenStorage || null === $this->authorizationChecker) {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if (null !== $token && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $requester = $this->userProvider->getUserForApiKey($event->getRequest()->headers->get('x-bikip-apikey'));
            $this->blameableListener->setUserValue($requester);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }
}
