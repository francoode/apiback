<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 12:13
 */

namespace AppBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use AppBundle\Handler\CompanyHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Company;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/api/v1")
 * @NamePrefix()
 * @Prefix("v1")
 */
class CompanyController extends BaseApiController implements IAppController
{



    /**
     * Get a single Company for the given id
     *
     * @Get("/companies/{id}.{_format}", defaults={"_format" = "json"}, name="api_get_company")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Companies",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     * @param $id
     * @return View
     */
    public function getCompanyAction($id)
    {
        $entity = $this->get('bikip_api.company.handler')->get($id);

        return $this->view($entity, Response::HTTP_OK)->setContext($this->context);
    }

    /**
     * Get a all Company
     *
     * @Get("/companies.{_format}", defaults={"_format" = "json"}, name="api_get_companys")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Companies",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function getCompanysAction()
    {
        $data = $this->get('bikip_api.company.handler')->all();

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Create a new Company from the submitted data
     *
     * @Post("/companies.{_format}", defaults={"_format" = "json"}, name="api_post_company")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Companies",
     *   input="AppBundle\Form\CompanyType",
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
    public function postCompanyAction(Request $request)
    {

        $data = $this->get('bikip_api.company.handler')->post(
            json_decode($request->getContent(), true) ?? []
        );

        return $this->view($data['message'], $data['status']);
    }

    /**
     * Update a Company partially
     *
     * @Patch("/companies/{id}.{_format}", defaults={"_format" = "json"}, name="api_patch_company")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Companies",
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
    public function patchCompanyAction(Request $request, Company $entity)
    {
        $data = $this->get('bikip_api.company.handler')->patch(
            $entity,
            json_decode($request->getContent(), true)
        );

        return $this->view($data['message'], $data['status']);
    }

    /**
     * Deletes a Company given the id
     *
     * @Delete("/companies/{id}.{_format}", defaults={"_format" = "json"}, name="api_delete_company")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Companies",
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
    public function deleteCompanyAction(Company $entity)
    {
        $data = $this->get('bikip_api.company.handler')->delete($entity);

        return $this->view($data, Response::HTTP_OK);
    }





}