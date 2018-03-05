<?php

namespace ApiSecurityBundle\Security;

use ApiBundle\Entity\User;
use ApiBundle\Service\HelperService;
use ApiSecurityBundle\Util\ApiKeyExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

/**
 * Autentica un request a partir de la existencia de un token válido en un header HTTP.
 */
class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /** @var ApiKeyExtractor */
    private $keyExtractor;
    /** @var HelperService */
    private $helperService;

    public function __construct(ApiKeyExtractor $keyExtractor, HelperService $helperService)
    {
        $this->keyExtractor = $keyExtractor;
        $this->helperService = $helperService;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiKeyUserProvider) {
            throw new \InvalidArgumentException(sprintf('The user provider must be an instance of ApiKeyUserProvider (%s was given)', get_class($userProvider)));
        }

        $apiKey = $token->getCredentials();
        /** @var User $user */
        $user = $userProvider->getUserForApiKey($apiKey);

        if (!$user) {
            throw new AuthenticationException('Invalid API Key', Response::HTTP_UNAUTHORIZED);
        }

//        if ($user->getApiKey()->isExpired()) {
//            throw new AuthenticationException('API Key Expired', Response::HTTP_UNAUTHORIZED);
//        }

        return new PreAuthenticatedToken(
            $user,
            $apiKey,
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return ($token instanceof PreAuthenticatedToken) && ($token->getProviderKey() === $providerKey);
    }

    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $this->keyExtractor->extract($request);

        if (!$apiKey) {
            throw new BadCredentialsException('No API key found', Response::HTTP_UNAUTHORIZED);
        }

        return new PreAuthenticatedToken('anon.', $apiKey, $providerKey);
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $error = $this->helperService->buildResponseErrorMessage($exception->getMessage(), $exception->getCode());
        return new JsonResponse($error['message'], $error['status']);
    }
}
