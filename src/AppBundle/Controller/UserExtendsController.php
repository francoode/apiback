<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 12:13
 */

namespace AppBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use ApiBundle\Form\UserType;
use AppBundle\Handler\UserExtendsHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use AppBundle\Entity\UserExtends;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Entity\User;

/**
 * @Route("/api/v1")
 * @NamePrefix()
 * @Prefix("v1")
 */
class UserExtendsController extends BaseApiController
{



    /**
     * Get a single UserExtends for the given id
     *
     * @Get("/users/{slug}.{_format}", defaults={"_format" = "json"}, name="api_get_userextends")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     * @param $id
     * @return View
     */
    public function getUserExtendsAction($slug)
    {
        $user = $this->get('kodear_api.user_service')->findUserBySlug($slug);

        return $this->view($user, Response::HTTP_OK)->setContext($this->context);
    }

    /**
     * Get a all UserExtends
     *
     * @Get("/users.{_format}", defaults={"_format" = "json"}, name="api_get_userextendss")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function getUserExtendssAction()
    {
        $data = $this->get('bikip_api.userextends.handler')->all();

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Deletes a UserExtends given the id
     *
     * @Delete("/users/{id}.{_format}", defaults={"_format" = "json"}, name="api_delete_userextends")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "User",
     *   statusCodes = {
     *     204 = "Resource successfully deleted",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     *
     * @return View
     */
    public function deleteUserExtendsAction(UserExtends $entity)
    {
        $data = $this->get('bikip_api.userextends.handler')->delete($entity);

        return $this->view($data, Response::HTTP_OK);
    }




}