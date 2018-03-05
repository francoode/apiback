<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 12:13
 */

namespace AppBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use AppBundle\Handler\TeamHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Team;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/api")
 * @NamePrefix()
 * @Prefix("v1")
 */
class TeamController extends BaseApiController
{



    /**
     * Get a single Team for the given id
     *
     * @Get("/teams/{id}.{_format}", defaults={"_format" = "json"}, name="api_get_team")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Teams",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     * @param $id
     * @return View
     */
    public function getTeamAction($id)
    {
        $entity = $this->get('bikip_api.team.handler')->get($id);

        return $this->view($entity, Response::HTTP_OK)->setContext($this->context);
    }

    /**
     * Get a all Team
     *
     * @Get("/teams.{_format}", defaults={"_format" = "json"}, name="api_get_teams")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Teams",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function getTeamsAction()
    {
        $data = $this->get('bikip_api.team.handler')->all();

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Create a new Team from the submitted data
     *
     * @Post("/teams.{_format}", defaults={"_format" = "json"}, name="api_post_team")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Teams",
     *   input="AppBundle\Form\TeamType",
     *   statusCodes = {
     *     201 = "Resource successfully created",
     *     400 = "Invalid data submitted",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function postTeamAction(Request $request)
    {

        $data = $this->get('bikip_api.team.handler')->post(
            json_decode($request->getContent(), true) ?? []
        );

        $this->context->setGroups(['basic_Team']);

        return $this->view($data['message'], $data['status'])->setContext($this->context);
    }

    /**
     * Update a Team partially
     *
     * @Patch("/teams/{id}.{_format}", defaults={"_format" = "json"}, name="api_patch_team")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Teams",
     *   statusCodes = {
     *     204 = "Resource successfully edited",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     *

     *
     * @return View
     */
    public function patchTeamAction(Request $request, Team $entity)
    {
        $this->get('bikip_api.team.handler')->patch(
            $entity,
            json_decode($request->getContent(), true)
        );

        return $this->view('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Deletes a Team given the id
     *
     * @Delete("/teams/{id}.{_format}", defaults={"_format" = "json"}, name="api_delete_team")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Teams",
     *   statusCodes = {
     *     204 = "Resource successfully deleted",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     *
     *
     * @return View
     */
    public function deleteTeamAction(Team $entity)
    {
        $this->get('bikip_api.team.handler')->delete($entity);

        return $this->view('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Add a user to team
     *
     * @Patch("/teams/{idTeams}/users/{idUser}/ ", defaults={"_format" = "json"}, name="api_patch_adduser")
     * @ParamConverter("idUser", class="ApiBundle:User")
     * @ParamConverter("idTeams", class="AppBundle:Team")
     * @ApiDoc(
     *   resource = true,
     *   section = "Teams",
     *   statusCodes = {
     *     204 = "Resource successfully edited",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     */
    public function addUserAction($idUser,$idTeams)
    {

        $this->get('bikip_api.team.handler')->addUserExtends($idUser, $idTeams);
        return $this->view('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Delete a user to team
     *
     * @Delete("/teams/{idTeams}/users/{idUser}/ ", defaults={"_format" = "json"}, name="api_delete_removeuser")
     * @ParamConverter("idUser", class="ApiBundle:User")
     * @ParamConverter("idTeams", class="AppBundle:Team")
     * @ApiDoc(
     *   resource = true,
     *   section = "Teams",
     *   statusCodes = {
     *     204 = "Resource successfully edited",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     */
    public function removeUserAction($idUser, $idTeams)
    {

        $this->get('bikip_api.team.handler')->removeUserExtends($idUser, $idTeams);
        return $this->view('', Response::HTTP_NO_CONTENT);
    }



}