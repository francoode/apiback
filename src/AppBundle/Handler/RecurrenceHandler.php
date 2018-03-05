<?php
namespace AppBundle\Handler;

use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\Recurrence;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\RecurrenceType;

class RecurrenceHandler extends EntitiesService
{


    public function get($id)
    {

        $entity = $this->em->getRepository('AppBundle:Recurrence')->find($id);

        return $entity;
    }

    public function all()
    {
        $entity = $this->em->getRepository('AppBundle:Recurrence')->findAll();
        return $entity;
    }

    public function post(array $parameters)
    {
        $entity = new Recurrence();

        $parameters = $this->container->get('bikip_api.auxiliarFunctions.service')->addStudyToParameters($parameters);

        return $this->processForm($entity, $parameters, 'POST');
    }

    public function patch(Recurrence $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }


    public function delete(Recurrence $entity)
    {
        $entityCloned = clone $entity;
        $this->em->remove($entity);
        $this->em->flush($entity);

        return $entityCloned;
    }

    public function getRecurrenceUsers(Recurrence $entity)
    {
        $data = $this->em->getRepository('AppBundle:UserExtends')->findBy(array('idRecurrence' => $entity->getId()));
        return $data;
    }

    private function processForm(Recurrence $entity, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(RecurrenceType::class, $entity, array('method' => $method));

        // submit parameters on the form
        $form->submit($parameters, 'PATCH' !== $method);

        // validate form
        if ($form->isValid()) {

            $entity = $form->getData();

            $this->em->persist($entity);
            $this->em->flush($entity);

            $statusCode = Response::HTTP_CREATED;
            if ('PATCH' === $method) {
                $statusCode = Response::HTTP_OK;
            }

            return $this->helperService->buildResponseSuccessMessage($entity, $statusCode);
        }

        return [
            'message' => $this->container->get('kodear_api.form_errors_service')->getFormErrors($form),
            'status' => Response::HTTP_BAD_REQUEST
        ];
    }

}