<?php
namespace ApiBundle\Service;

use ApiBundle\Entity\Productor;
use ApiBundle\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService extends EntitiesService
{
    protected $registerConfirmationRequired;
    protected $tokenGenerator;

    public function setRegisterConfirmationRequired($registerConfirmationRequired)
    {
        $this->registerConfirmationRequired = $registerConfirmationRequired;
    }

    public function setTokenGenerator($tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * Finds the User entity by id
     *
     * @param  integer $id
     *
     * @return User $user
     * @throws 404 if the user couldn't be found
     */
    public function findUserById($id)
    {
        $user = $this->em->getRepository('ApiBundle:User')->find($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        return $user;
    }

    /**
     * @param string $slug in this context can be a user's id, username or email
     *
     * @return User or NotFoundException
     */
    public function findUserBySlug($slug)
    {
        $user = $this->em->getRepository('ApiBundle:User')->findOneBySlug($slug);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        return $user;
    }

    /**
     * @param $username
     *
     * @return User|false
     */
    public function isUserRegistered($username)
    {
        $user = $this->em->getRepository('ApiBundle:User')->findOneBy(['username' => $username]);

        if (!$user) {
            return false;
        }

        return $user;
    }

    public function register(Request $request, Form $form)
    {
        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
                $userManager = $this->container->get('fos_user.user_manager');
                $user = $form->getData();
                
                // enable user (check if confirmation is required)
                if ($this->registerConfirmationRequired) {
                    $user->setEnabled(false);

                    // generate confirmation token
                    $user->setConfirmationToken($this->tokenGenerator->generateToken());
                } else {
                    $user->setEnabled(true);
                }

                $user->setUsername($user->getEmail());

                // Set api key
                $apiKey = $this->container->get('api_security.user_api_key_service')->generateKeysForUser($user);
                $user->setApiKey($apiKey);
                $userManager->updateUser($user);
            } catch (\Exception $e) {
                // Error imprevisto
                $msg = $e->getMessage();

                return $this->helperService->buildResponseErrorMessage(
                    "Error to create user: $msg",
                    Response::HTTP_CONFLICT
                );
            }

            if ($this->registerConfirmationRequired) {
                try {
                    // Enviar email de welcome
                    $emailHelper = $this->container->get('kodear_api.email_messages_service');

                    $emailHelper->sendWelcomeHtmlEmailMessage($user, $request->request->get('plainPassword'));
                } catch (\Exception $e) {
                    // falla al enviar el email. Retornamos la resp igualmente para que no trabe.
                    return $this->helperService->buildResponseSuccessMessage($user, Response::HTTP_CREATED);
                }
            }

            return $this->helperService->buildResponseSuccessMessage($user, Response::HTTP_CREATED);
        }

        return [
            'message' => $this->container->get('kodear_api.form_errors_service')->getFormErrors($form),
            'status' => Response::HTTP_BAD_REQUEST
        ];
    }

    /**
     * Confirm registration
     * 
     * @param Request $request Given HTTP request
     * 
     * @return Response
     */
    public function registerConfirm(Request $request)
    {
        $token = $request->request->get('confirmationToken');

        if (null == $token) {
            return [
                'message' => array('error' => 'The confirmation token is required'),
                'status' => Response::HTTP_BAD_REQUEST
            ];
        }

        // get user manager
        $userManager = $this->container->get('fos_user.user_manager');
        
        // get user by given token
        $user = $userManager->findUserByConfirmationToken($token);

        if (null == $user) {
            return [
                'message' => array('error' => 'Invalid confirmation token'),
                'status' => Response::HTTP_BAD_REQUEST
            ];
        }

        // enable user
        $user->setEnabled(true);

        // clean confirmation token
        $user->setConfirmationToken(null);

        // update user
        $userManager->updateUser($user, true);

        return $this->helperService->buildResponseSuccessMessage($user, Response::HTTP_OK);
    }

    /**
     * Prepara el proceso de password reset generando token y envio de email
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function preparePasswordReset(Request $request)
    {
        $user = $this->em->getRepository('ApiBundle:User')->getUserByUserName($request->request->get('username'));

        if (!$user) {
            return $this->helperService->buildResponseErrorMessage('User not found.', Response::HTTP_NOT_FOUND);
        }

        // Si no tiene token, lo genero y se lo mando por email.
        return $this->generateResetPassToken($user, $request->request->get('url'));
    }

    /**
     * Hace el cambio de password del User
     *
     * @param  Request $request
     *
     * @return array
     */
    public function executeResetPassword(Request $request)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var User $user */
        $user = $userManager->findUserByConfirmationToken($request->request->get('confirmation_token'));

        if (null === $user) {
            return $this->helperService->buildResponseErrorMessage('Invalid token.', Response::HTTP_BAD_REQUEST);
        }

        // Seteo el mail que corresponde al confirmation_token
        $request->request->set('username', $user->getUsername());
        if ($request->request->get('plainPassword')) {
            $user->setPlainPassword($request->request->get('plainPassword'));
            $user->setConfirmationToken(null);
            $userManager->updateUser($user);

            return $this->helperService->buildResponseSuccessMessage(
                'Reset password finished',
                Response::HTTP_OK
            );
        }

        return $this->helperService->buildResponseErrorMessage('Bad request', Response::HTTP_BAD_REQUEST);
    }

    /**
     * Genera el confirmation_token del usuario
     *
     * @param  User $user
     * @param  string $url
     *
     * @return array
     */
    private function generateResetPassToken(User $user, $url)
    {
        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $user->setPasswordRequestedAt(new \DateTime());

        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($user);

        $emailHelper = $this->container->get('kodear_api.email_messages_service');
        $emailHelper->sendPasswordResetEmail($user, $url);

        return $this->helperService->buildResponseSuccessMessage(
            'Se envía confirmation token al email.',
            Response::HTTP_OK
        );
    }
}
