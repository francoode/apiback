<?php
namespace AppBundle\Handler;
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 13:46
 */
use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\Business;
use AppBundle\Entity\Note;
use AppBundle\Entity\UserExtends;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\NoteType;

class NoteHandler extends EntitiesService
{


    public function get($entity, $id)
    {
        $repository = 'AppBundle:'.ucfirst($entity);
        $entity = $this->em->getRepository($repository)->find($id);

        $noteCollection = (is_null($entity->getnoteCollection())) ? null : $entity->getnoteCollection();

        return $noteCollection;
    }


    public function post(array $parameters)
    {
        $entity = new Note();
        $parameters = $this->container->get('bikip_api.auxiliarFunctions.service')->addStudyAndUserOwnerToParameters($parameters);

        return $this->processForm($entity, $parameters, 'POST');
    }

    public function patch($id, array $parameters)
    {
        $entity = $this->em->getRepository('AppBundle:Note')->find($id);
        return $this->processForm($entity, $parameters, 'PATCH');
    }


    public function delete($id)
    {
        $entity = $this->em->getRepository('AppBundle:Note')->find($id);
        $entityCloned = clone $entity;
        $this->em->remove($entity);
        $this->em->flush($entity);

        return $entityCloned;
    }

    private function processForm(Note $entity, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(NoteType::class, $entity, array('method' => $method));

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
}
