<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 12:13
 */

namespace AppBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use AppBundle\Handler\BusinessHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Business;
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
class BusinessController extends BaseApiController
{
    /**
     * Get a single Business for the given id
     *
     * @Get("/businesses/{id}.{_format}", defaults={"_format" = "json"}, name="api_get_business")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Businesses",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     * @param $id
     * @return View
     */
    public function getBusinessAction($id)
    {
        $entity = $this->get('bikip_api.business.handler')->get($id);

        return $this->view($entity, Response::HTTP_OK)->setContext($this->context);
    }

    /**
     * Get a all Business
     *
     * @Get("/businesses.{_format}", defaults={"_format" = "json"}, name="api_get_business")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Businesses",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function getBusinesssAction()
    {
        $data = $this->get('bikip_api.business.handler')->all();

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Create a new Business from the submitted data
     *
     * @Post("/businesses.{_format}", defaults={"_format" = "json"}, name="api_post_business")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Businesses",
     *   input="AppBundle\Form\BusinessType",
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
    public function postBusinessAction(Request $request)
    {

        $data = $this->get('bikip_api.business.handler')->post(
            json_decode($request->getContent(), true) ?? []
        );

        $this->context->setGroups(['basic_Business']);

        return $this->view($data['message'], $data['status'])->setContext($this->context);
    }

    /**
     * Update a Business partially
     *
     * @Patch("/businesses/{id}.{_format}", defaults={"_format" = "json"}, name="api_patch_business")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Businesses",
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
    public function patchBusinessAction(Request $request, Business $entity)
    {
        $data = $this->get('bikip_api.business.handler')->patch(
            $entity,
            json_decode($request->getContent(), true)
        );

        return $this->view($data['message'], $data['status']);
    }

    /**
     * Deletes a Business given the id
     *
     * @Delete("/businesses/{id}.{_format}", defaults={"_format" = "json"}, name="api_delete_business")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Businesses",
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
    public function deleteBusinessAction(Business $entity)
    {
        $data = $this->get('bikip_api.business.handler')->delete($entity);

        return $this->view($data, Response::HTTP_OK);
    }


    /**
     * Add a contact to business
     *
     * @Patch("/businesses/{idBusiness}/contacts/{idContact}/ ", defaults={"_format" = "json"}, name="api_patch_addcontact")
     * @ParamConverter("idBusiness", class="AppBundle:Business")
     * @ParamConverter("idContact", class="AppBundle:Contact")
     * @ApiDoc(
     *   resource = true,
     *   section = "Businesses",
     *   statusCodes = {
     *     204 = "Resource successfully edited",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     */
    public function addContactAction($idBusiness,$idContact)
    {

        $this->get('bikip_api.business.handler')->addContact($idBusiness, $idContact);
        return $this->view('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Delete a contact to business
     *
     * @Delete("/businesses/{idBusiness}/contacts/{idContact}/ ", defaults={"_format" = "json"}, name="api_delete_removecontact")
     * @ParamConverter("idBusiness", class="AppBundle:Business")
     * @ParamConverter("idContact", class="AppBundle:Contact")
     * @ApiDoc(
     *   resource = true,
     *   section = "Businesses",
     *   statusCodes = {
     *     204 = "Resource successfully edited",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     */
    public function removeContactAction($idBusiness, $idContact)
    {

        $this->get('bikip_api.business.handler')->removeContact($idBusiness, $idContact);
        return $this->view('', Response::HTTP_NO_CONTENT);
    }




}