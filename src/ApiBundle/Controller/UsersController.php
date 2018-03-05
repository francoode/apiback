<?php
namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Post;
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
class UsersController extends BaseApiController
{
    /**
     * Retrieve user by given slug (id or email).
     *
     * @Get("/public/users/{slug}.{_format}", defaults={"_format" = "json"}, name="api_v1_get_users")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Users",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Returned when the User is not found"
     *   }
     * )
     *
     * @param string $slug
     *
     * @return View
     */
    public function getUserAction($slug)
    {
        $user = $this->get('kodear_api.user_service')->findUserBySlug($slug);

        return $this->view($user, Response::HTTP_OK)->setContext($this->context);
    }
}
