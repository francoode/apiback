<?php

namespace ApiBundle\Service;

use ApiBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Service para enviar distintos Emails
 *
 * @package ApiBundle\Service
 */
class EmailMessagesService
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(Container $container, $mailer)
    {
        $this->container = $container;
        $this->mailer = $mailer;
    }

    /**
     * Password reset
     *
     * @param User   $user
     * @param string $url
     */
    public function sendPasswordResetEmail($user, $url)
    {
        $emailTemplate = $this->container->getParameter('api.password_reset_template');;

        $brand = $this->container->getParameter('api.client.brand');
        $appBaseUrl = $this->container->getParameter('api.client.base_url');
        $appPasswordResetUrl = $this->container->getParameter('api.client.password_reset_url');

        $subject = $brand . ' - Restablecer Contraseña';
        $email = $user->getEmail(); // para mandar
        

        $data['passwordResetUrl'] = $appBaseUrl . $appPasswordResetUrl . $user->getConfirmationToken();
        $data['brand'] = $brand;
        
        $htmlMessage = $this->container->get('twig')->render($emailTemplate, $data);

        $this->sendEmailMessage($email, $subject, $htmlMessage);
    }

    /**
     * User welcome
     *
     * @param User $user
     * @param string $plainPassword
     */
    public function sendWelcomeHtmlEmailMessage(User $user, $plainPassword)
    {
        $emailTemplate = $this->container->getParameter('api.registration_confirm_template');;

        $brand = $this->container->getParameter('api.client.brand');
        $appBaseUrl = $this->container->getParameter('api.client.base_url');
        $appRegistrationConfirmUrl = $this->container->getParameter('api.client.registration_confirm_url');

        $subject = 'Bienvenido a ' . $brand;
        $email = $user->getEmail(); // para mandar
        
        $data['registerConfirmUrl'] = $appBaseUrl . $appRegistrationConfirmUrl . $user->getConfirmationToken();
        $data['brand'] = $brand;

        $htmlMessage = $this->container->get('twig')->render($emailTemplate, $data);

        $this->sendEmailMessage($email, $subject, $htmlMessage);
    }

    /**
     * Send email using SwiftMailer
     *
     * @param  string $email
     * @param  string $subject
     * @param  string $body
     */
    private function sendEmailMessage($email, $subject, $body)
    {
        $brand = $this->container->getParameter('api.client.brand');

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array($this->container->getParameter('from_email') => $brand))
            ->setTo($email)
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}
