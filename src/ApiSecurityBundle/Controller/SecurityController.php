<?php
namespace ApiSecurityBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Prefix;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/v1")
 * @NamePrefix()
 * @Prefix("v1")
 */
class SecurityController extends BaseApiController
{
    /**
     * @Post("/public/login.{_format}", defaults={"_format" = "json"}, name="security.get_token")
     *
     * @ApiDoc(
     *   resource = true,
     *   section= "Security",
     *   description = "Return the data, the access token, refresh token and expiration date by given user credentials",
     *   input="ApiSecurityBundle\Form\LoginType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when no API key was found"
     *   }
     * )
     * @param Request $request
     *
     * @return View
     */
    public function getTokenAction(Request $request)
    {
        $apiKeyService = $this->get('api_security.user_api_key_service');
        $data = $apiKeyService
            ->getApiKeyForUser(
                $request->request->get('username'), 
                $request->request->get('password')
            )
        ;

        $this->context->setGroups(['login']);

        return $this->view($data, Response::HTTP_OK)->setContext($this->context);
    }

    /**
     * Generates and returns the new access token, refresh token and expiration date from a User
     *
     * @Post("/public/refreshAccessToken/{refresh_token}.{_format}", defaults={"_format" = "json"}, name="security.refresh_access_token")
     *
     * @ApiDoc(
     *   resource = true,
     *   section= "Security",
     *   description = "Generates and returns the new access token, refresh token and expiration date from a User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when no Api Key is found"
     *   }
     * )
     * @param string $refresh_token
     *
     * @return View
     */
    public function refreshAccessToken($refresh_token)
    {
        $apiKeyService = $this->get('api_security.user_api_key_service');
        $data = $apiKeyService->refreshToken($refresh_token);

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Return the refresh token
     *
     * @Get("/public/refreshToken/{access_token}.{_format}", defaults={"_format" = "json"}, name="security.get_refresh_token")
     *
     * @ApiDoc(
     *   resource = true,
     *   section= "Security",
     *   description = "Return the refresh token",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when no Api Key is found"
     *   }
     * )
     * @param string $access_token
     *
     * @return View
     */
    public function getRefreshToken($access_token)
    {
        $apiKeyService = $this->get('api_security.user_api_key_service');
        $data = $apiKeyService->getRefreshToken($access_token);

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Invalidates the current access token and generates a new one.
     *
     * @Post("/renewAccessToken.{_format}", defaults={"_format" = "json"}, name="security.renew_access_token")
     *
     * @ApiDoc(
     *   resource = true,
     *   section= "Security",
     *   description = "Invalidates the current access token and generates a new one.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when no Api Key is found"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function renewAccessToken(Request $request)
    {
        $requester = $this->get('api_security.api_key_user_provider')->getUserForApiKey(
            $request->headers->get('x-bikip-apikey')
        );

        $apiKeyService = $this->get('api_security.user_api_key_service');
        $data = $apiKeyService->renewAccessToken($requester);

        $this->context->setGroups(['login']);

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Validates if the access token is valid
     *
     * @Post("/public/isAccessTokenValid/{access_token}.{_format}", defaults={"_format" = "json"}, name="security.valid_access_token")
     *
     * @ApiDoc(
     *   resource = true,
     *   section= "Security",
     *   description = "Validates if the access token is valid",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when no Api Key is found"
     *   }
     * )
     *
     * @param string $access_token
     *
     * @return View
     */
    public function isAccessTokenValid($access_token)
    {
        $data = $this->get('api_security.user_api_key_service')->isAccessKeyValid($access_token);

        return $this->view($data, Response::HTTP_OK);
    }
}
