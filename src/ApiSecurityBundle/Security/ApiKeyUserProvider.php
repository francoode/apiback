<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 8/10/15
 * Time: 8:25 PM
 */

namespace ApiSecurityBundle\Security;


use Doctrine\ORM\EntityManager;
use ApiBundle\Entity\Repository\UsersRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @var UsersRepository
     */
    private $usersRepository;

    public function __construct(EntityManager $em)
    {
        $this->usersRepository = $em->getRepository('ApiBundle:User');
    }


    public function getUsernameForApiKey($apiKey)
    {
        $user = $this->usersRepository->getUserByApiKey($apiKey);
        return ($user != null) ? $user->getUsername() : null;
    }

    /**
     * Devuelve el User de la $apiKey
     * @param  string $apiKey
     * @return User $user
     */
    public function getUserForApiKey($apiKey)
    {
        $user = $this->usersRepository->getUserByApiKey($apiKey);
        return $user;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $user = $this->usersRepository->getUserByUserName($username);

        if ($user == null) {
            $exception = new UsernameNotFoundException();
            $exception->setUsername($username);
            throw $exception;
        }

        return new User($username, $user->getPassword(), $user->getRoles());
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return ('\Symfony\Component\Security\Core\User\User' === $class);
    }
}
