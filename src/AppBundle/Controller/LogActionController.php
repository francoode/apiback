<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 12:13
 */

namespace AppBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use AppBundle\Entity\Contact;
use AppBundle\Handler\LogActionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use AppBundle\Entity\LogAction;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/api/v1")
 * @NamePrefix()
 * @Prefix("v1")
 */
class LogActionController extends BaseApiController
{
    /**
     * Get all logs for only one entity object
     * .entity valids = { contact , business , company }
     *
     *
     * @Get("/{entity}/{id}/logactions.{_format}", defaults={"_format" = "json"}, name="api_get_log")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "LogActions",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     * @param $id
     * @return View
     */
    public function getLogsAction($id, $entity)
    {
        $entity = $this->defineEntityOrigin($entity);

        $entity = $this->get('bikip_api.logaction.handler')->get($entity, $id);

        return $this->view($entity, Response::HTTP_OK)->setContext($this->context);
    }


    /**
     * Create a new LogAction for an entity
     *
     * @Post("/{entity}/{id}/logactions", defaults={"_format" = "json"}, name="api_post_logaction")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "LogActions",
     *   input="AppBundle\Form\LogActionType",
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
    public function postLogAction(Request $request, $entity, $id)
    {
        $entity = $this->defineEntityOrigin($entity);

        $parameters = $request->request->all();
        $parameters[$entity][] = $id;

        $data = $this->get('bikip_api.logaction.handler')->post($parameters);

        $this->context->setGroups(['basic_LogAction']);

        return $this->view($data['message'], $data['status'])->setContext($this->context);
    }

    /**
     * Update a LogAction partially, for an entity
     *
     * @Patch("/{entity}/{id}/logactions/{idLog}", defaults={"_format" = "json"}, name="api_patch_logaction")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "LogActions",
     *   statusCodes = {
     *     200 = "Resource successfully edited",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     *
     *
     * @return View
     */
    public function patchLogAction(Request $request, $id, $entity, $idLog)
    {

        $data = $this->get('bikip_api.logaction.handler')->patch(
            $idLog,
            json_decode($request->getContent(), true)
        );

        return $this->view($data['message'], $data['status']);
    }

    /**
     * Deletes a LogAction given the id
     *
     * @Delete("/{entity}/{id}/logactions/{idLog}", defaults={"_format" = "json"}, name="api_delete_logaction")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "LogActions",
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
    public function deleteLogAction($id, $entity, $idLog)
    {

        $data = $this->get('bikip_api.logaction.handler')->delete($idLog);

        return $this->view($data, Response::HTTP_OK);
    }

    private function defineEntityOrigin($origin)
    {
        if($origin == LogAction::contacts)
        {
            return LogAction::contact;
        }
        else if($origin == LogAction::businesses)
        {
            return LogAction::business;
        }
        else if($origin == LogAction::companies)
        {
            return LogAction::company;
        }
        else
        {
            return null;
        }
    }


}