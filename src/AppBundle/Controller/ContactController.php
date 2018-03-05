<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 08/01/18
 * Time: 12:54
 */

namespace AppBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use AppBundle\Handler\ContactHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Contact;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Company;

/**
 * @Route("/api/v1")
 * @NamePrefix()
 * @Prefix("v1")
 */
class ContactController extends BaseApiController implements IAppController
{



    /**
     * Get a single Contact for the given id
     *
     * @Get("/contacts/{id}.{_format}", defaults={"_format" = "json"}, name="api_get_contact")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Contacts",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     * @param $id
     * @return View
     */
    public function getContactAction($id)
    {
        $entity = $this->get('bikip_api.contact.handler')->get($id);

        return $this->view($entity, Response::HTTP_OK)->setContext($this->context);
    }

    /**
     * Get a all Contact
     *
     * @Get("/contacts.{_format}", defaults={"_format" = "json"}, name="api_get_contacts")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Contacts",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function getContactsAction()
    {
        $data = $this->get('bikip_api.contact.handler')->all();

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Create a new Contact from the submitted data
     *
     * @Post("/contacts.{_format}", defaults={"_format" = "json"}, name="api_post_contact")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Contacts",
     *   input="AppBundle\Form\ContactType",
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
    public function postContactAction(Request $request)
    {
        $data = $this->get('bikip_api.contact.handler')->post(
            json_decode($request->getContent(), true) ?? []
        );

        return $this->view($data['message'], $data['status']);
    }

    /**
     * Update a Contact partially
     *
     * @Patch("/contacts/{id}.{_format}", defaults={"_format" = "json"}, name="api_patch_contact")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Contacts",
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
    public function patchContactAction(Request $request, Contact $entity)
    {
        $data = $this->get('bikip_api.contact.handler')->patch(
            $entity,
            json_decode($request->getContent(), true)
        );


        return $this->view($data['message'], $data['status']);
    }

    /**
     * Deletes a Contact given the id
     *
     * @Delete("/contacts/{id}.{_format}", defaults={"_format" = "json"}, name="api_delete_contact")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Contacts",
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
    public function deleteContact(Contact $entity)
    {
        $data = $this->get('bikip_api.contact.handler')->delete($entity);

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Add a company to contact
     *
     * @Patch("/contacts/{idContact}/companies/{idCompany}/", defaults={"_format" = "json"}, name="api_patch_addcompany")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Contacts",
     *   statusCodes = {
     *     204 = "Resource successfully edited",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     */
    public function addCompanyAction(Contact $idContact,Company $idCompany)
    {
        $this->get('bikip_api.contact.handler')->addCompany($idContact, $idCompany);
        return $this->view('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Deletes a company to contact
     *
     * @Delete("/contacts/{idContact}/companies/{idCompany}/", defaults={"_format" = "json"}, name="api_delete_deletecompany")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Contacts",
     *   statusCodes = {
     *     204 = "Resource successfully deleted",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     */
    public function deleteCompanyAction(Contact $idContact,Company $idCompany)
    {
        $this->get('bikip_api.contact.handler')->deleteCompany($idContact, $idCompany);

        return $this->view('', Response::HTTP_NO_CONTENT);
    }
}