<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 12:13
 */

namespace AppBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use AppBundle\Handler\StudyHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Study;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/api/v1")
 * @NamePrefix()
 * @Prefix("v1")
 */
class StudyController extends BaseApiController implements IAppController
{



    /**
     * Get a single Study for the given id
     *
     * @Get("/studies/{id}.{_format}", defaults={"_format" = "json"}, name="api_get_study")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Studies",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     * @param $id
     * @return View
     */
    public function getStudyAction($id)
    {
        $entity = $this->get('bikip_api.study.handler')->get($id);

        return $this->view($entity, Response::HTTP_OK)->setContext($this->context);
    }

    /**
     * Get a all Study
     *
     * @Get("/studies.{_format}", defaults={"_format" = "json"}, name="api_get_studys")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Studies",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function getStudysAction()
    {
        $data = $this->get('bikip_api.study.handler')->all();

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Create a new Study from the submitted data
     *
     * @Post("/studies.{_format}", defaults={"_format" = "json"}, name="api_post_study")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Studies",
     *   input="AppBundle\Form\StudyType",
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
    public function postStudyAction(Request $request)
    {

        $data = $this->get('bikip_api.study.handler')->post(
            json_decode($request->getContent(), true) ?? []
        );

        $this->context->setGroups(['basic_Study']);

        return $this->view($data['message'], $data['status'])->setContext($this->context);
    }

    /**
     * Update a Study partially
     *
     * @Patch("/studies/{id}.{_format}", defaults={"_format" = "json"}, name="api_patch_study")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Studies",
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
    public function patchStudyAction(Request $request, Study $entity)
    {
        $data = $this->get('bikip_api.study.handler')->patch(
            $entity,
            json_decode($request->getContent(), true)
        );

        return $this->view($data['message'], $data['status']);
    }

    /**
     * Deletes a Study given the id
     *
     * @Delete("/studies/{id}.{_format}", defaults={"_format" = "json"}, name="api_delete_study")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Studies",
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
    public function deleteStudy(Study $entity)
    {
        $data = $this->get('bikip_api.study.handler')->delete($entity);

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Get users of a study
     *
     * @Get("/studies/{id}/users/", defaults={"_format" = "json"}, name="api_get_studies_users")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Studies",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     * @param $id
     * @return View
     */

    public function getStudiesUsersAction(Study $entity)
    {
        $data = $this->get('bikip_api.study.handler')->getStudyUsers($entity);

        return $this->view($data,Response::HTTP_OK);
    }



}