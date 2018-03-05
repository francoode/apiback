<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/email")
 */
class EmailController extends FOSRestController
{
    /**
     * Test emails.
     *
     * @Post("/welcome", name="email_test_welcome")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function emailTestApiAction(Request $request)
    {
        $email = $request->get('email');
        $this->get('kodear_api.email_messages_service')->sendWelcomeHtmlEmailMessage($email);

        return new JsonResponse('Email enviado');
    }
}
