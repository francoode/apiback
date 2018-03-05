<?php

/**
 * Copyright (c) 2015 - Kodear
 */

namespace ApiSecurityBundle\Service;

use Doctrine\ORM\EntityManager;
use ApiBundle\Entity\ApiKey;
use ApiBundle\Entity\Repository\UsersRepository;
use ApiBundle\Entity\User;
use ApiSecurityBundle\Util\ApiKeyGenerator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Servicio para el manejo de las API KEY de usuarios.
 *
 * @package ApiSecurityBundle\Service
 */
class UserApiKeyService
{
    /**
     * @var UsersRepository
     */
    private $userRepository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    /**
     * @var ApiKeyGenerator
     */
    private $keyGenerator;
    private $expirationDays;

    /**
     * Constructor
     * 
     * @param EntityManager $em Entity manager
     * @param EncoderFactory $encoderFactory Encoder
     * @param ApiKeyGenerator $keyGenerator Key generator
     * @param integer $expirationDays Expiration days
     */
    public function __construct(
        EntityManager $em,
        EncoderFactory $encoderFactory,
        ApiKeyGenerator $keyGenerator,
        $expirationDays
    ) {
        $this->em = $em;
        $this->userRepository = $em->getRepository('ApiBundle:User');
        $this->encoderFactory = $encoderFactory;
        $this->keyGenerator = $keyGenerator;
        $this->expirationDays = $expirationDays;
    }

    /**
     * Retrieve API key for user
     * 
     * @param string $username Given username
     * @param string $password Given password
     * 
     * @return Array
     */
    public function getApiKeyForUser($username, $password)
    {
        /** @var User $user */
        $user = $this->userRepository->getUserByUserName($username);

        if ($this->validateLogin($user, $password)) {
            $apiKey = $user->getApiKey();

//            if ($apiKey->isExpired()) {
//                $oldApiKey = $apiKey;
//                $apiKey = $this->generateKeysForUser($user);
//
//                $this->em->remove($oldApiKey);
//                $this->em->flush();
//            }

            $data['access_token'] = $apiKey->getAccessKey();
            $data['refresh_token'] = $apiKey->getRefreshKey();
            $data['expiration_datetime'] = $apiKey->getExpiresAt();
            $data['user'] = $user;

            return $data;
        }

        return false;
    }

    public function generateKeysForUser(User $user)
    {
        $apiKey = new ApiKey();
        $apiKey->setAccessKey($this->keyGenerator->generate());
        $apiKey->setRefreshKey($this->keyGenerator->generate());
        $apiKey->setUser($user);

        $now = new \DateTime();
        $apiKey->setCreatedAt($now);
//        $apiKey->setExpiresAt($now->add(new \DateInterval(sprintf('P%dD', $this->expirationDays))));
        $apiKey->setExpiresAt(null);

        $user->setApiKey($apiKey);

        $this->em->persist($apiKey);
        $this->em->persist($user);
        $this->em->flush();

        return $apiKey;
    }

    /**
     * @param $user User
     * @param $password
     *
     * @return bool
     */
    private function validateLogin($user, $password)
    {
        if (!$user) {
            throw new HttpException(401, "Bad credentials");
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $isPasswordValid = $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());

        if (!$isPasswordValid) {
            throw new HttpException(401, "Bad credentials");
        }

        if (in_array('ROLE_API', $user->getRoles())) {
            return true;
        }

        if (!$user->isEnabled()) {
            throw new HttpException(401, "Email not confirmed.");
        }

        return true;
    }

    public function refreshToken($refreshKey)
    {
        $user = $this->userRepository->getUserByRefreshKey($refreshKey);
        if (!$user) {
            throw new NotFoundHttpException("Refresh Key not found");
        }
        $oldApiKey = $user->getApiKey();

        $apiKey = $this->generateKeysForUser($user);
        $data['access_token'] = $apiKey->getAccessKey();
        $data['refresh_token'] = $apiKey->getRefreshKey();
        $data['expiration_datetime'] = $apiKey->getExpiresAt();
        $data['user'] = $user;
        $data['roles'] = $user->getRoles();

        $this->em->remove($oldApiKey);
        $this->em->flush();

        return $data;
    }

    public function getRefreshToken($accessKey)
    {
        $user = $this->userRepository->getUserByApiKey($accessKey);
        if (!$user) {
            throw new NotFoundHttpException("API Key not found");
        }

        return $user->getApiKey()->getRefreshKey();
    }

    public function renewAccessToken(User $user)
    {
        $oldApiKey = $user->getApiKey();
        $apiKey = $this->generateKeysForUser($user);

        $this->em->remove($oldApiKey);
        $this->em->flush();

        $data['access_token'] = $apiKey->getAccessKey();
        $data['refresh_token'] = $apiKey->getRefreshKey();
        $data['expiration_datetime'] = $apiKey->getExpiresAt();
        $data['user'] = $user;
        $data['roles'] = $user->getRoles();

        return $data;
    }

    public function isAccessKeyValid($access_token)
    {
        if (!$access_token || $access_token == "{access_token}") {
            throw new BadRequestHttpException('No access_token given');
        }
        $user = $this->em->getRepository('ApiBundle:User')->getUserByApiKey($access_token);

        if ($user) {
            return true;
        }
        return false;
    }
}
