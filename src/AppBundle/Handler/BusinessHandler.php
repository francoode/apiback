<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 03/01/18
 * Time: 13:55
 */

namespace AppBundle\Handler;

use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\Business;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\BusinessType;
use ApiBundle\Entity\User;

class BusinessHandler extends EntitiesService
{
    public function get($id)
    {

        $entity = $this->em->getRepository('AppBundle:Business')->find($id);

        return $entity;
    }

    public function all()
    {
        $entity = $this->em->getRepository('AppBundle:Business')->findAll();
        return $entity;
    }

    public function post(array $parameters)
    {
        $entity = new Business();
        $parameters = $this->container->get('bikip_api.auxiliarFunctions.service')->addStudyToParameters($parameters);

        return $this->processForm($entity, $parameters, 'POST');
    }

    public function patch(Business $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }


    public function delete(Business $entity)
    {
        $entityCloned = clone $entity;
        $this->em->remove($entity);
        $this->em->flush($entity);

        return $entityCloned;
    }

    private function processForm(Business $entity, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(BusinessType::class, $entity, array('method' => $method));

        // submit parameters on the form
        $form->submit($parameters, 'PATCH' !== $method);

        // validate form
        if ($form->isValid()) {

            $entity = $form->getData();

            $this->em->persist($entity);
            $this->em->flush($entity);

            return $this->helperService->buildResponseSuccessMessage($entity, Response::HTTP_CREATED);
        }

        return [
            'message' => $this->container->get('kodear_api.form_errors_service')->getFormErrors($form),
            'status' => Response::HTTP_BAD_REQUEST
        ];
    }

    public function addContact($idBusiness, $idContact)
    {
        $idBusiness->addContactCollection($idContact);
        $this->em->flush($idBusiness);
    }

    public function removeContact($idBusiness, $idContact)
    {
        $idBusiness->removeContactCollection($idContact);
        $this->em->flush($idBusiness);
    }

}