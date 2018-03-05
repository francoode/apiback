<?php
namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use ApiBundle\Form\ResettingPasswordType;
use ApiBundle\Form\UserType;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/v1")
 * @NamePrefix()
 * @Prefix("v1")
 */
class RegistrationController extends BaseApiController
{
    /**
     * Confirm user registration
     *
     * @Patch("/public/register.{_format}", defaults={"_format" = "json"}, name="api_v1_register_confirm")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Register",
     *   description = "Confirm user registration",
     *   parameters={
     *      {
     *          "name"="confirmationToken",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="Valid confirmation token"
     *      }
     *   },
     *   statusCodes = {
     *     200 = "Register successfully confirmed",
     *     400 = "Invalid data submitted"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function patchRegisterAction(Request $request)
    {
        $data = $this->get('kodear_api.user_service')->registerConfirm($request);

        return $this->view($data['message'], $data['status']);
    }

    /**
     * Register a new user
     *
     * @Post("/public/register.{_format}", defaults={"_format" = "json"}, name="api_v1_register")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Register",
     *   description = "Register a new user",
     *   input="ApiBundle\Form\UserType",
     *   statusCodes = {
     *     201 = "Resource successfully created",
     *     400 = "Invalid data submitted",
     *     409 = "Couldn't create user due to a database exception (most likely a constraint)",
     *   }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function registerAction(Request $request)
    {
        $form = $this->getForm(UserType::class, new User(), "POST");
        $data = $this->get('kodear_api.user_service')->register($request, $form);

        return $this->view($data['message'], $data['status']);
    }

    /**
     * Check if the user is registered
     *
     * @Get("/public/isRegistered/{username}.{_format}", defaults={"_format" = "json"}, name="api_v1_is_registered")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Register",
     *   description = "Check if the user is registered",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param string $username
     *
     * @return View
     */
    public function isRegisteredAction($username)
    {
        $productor = $this->get('kodear_api.user_service')->isUserRegistered($username);

        $this->context->setGroups(['is_registered']);

        return $this->view($productor, Response::HTTP_OK)->setContext($this->context);
    }

    /**
     * Reset password
     *
     * @Post("/public/passwordReset", defaults={"_format" = "json"}, name="api_v1_password_reset")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Register",
     *   description = "Reset password",
     *   input="ApiBundle\Form\ResettingPasswordType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = {
     *         "Returned when invalid data is submitted.",
     *         "Returned when the User's email is not confirmed.",
     *         "Returned when the Password was already requested."
     *     },
     *     404 = {
     *         "Returned when the user is not found",
     *         "Returned when the confirmation_token is not found",
     *     }
     *   }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function passwordReset(Request $request)
    {
        // Si se hace un request con un token y el nuevo password, se resetea el password
        if ($request->request->get('confirmation_token') && $request->request->get('plainPassword')) {
            $data = $this->get('kodear_api.user_service')->executeResetPassword($request);
        } else {
            $data = $this->get('kodear_api.user_service')->preparePasswordReset($request);
        }

        return $this->view($data['message'], $data['status']);
    }
}
