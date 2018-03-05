<?php
namespace AppBundle\Handler;
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 13:46
 */
use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\Task;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\TaskType;
use ApiBundle\Entity\User;

class TaskHandler extends EntitiesService
{


    public function get($id)
    {

        $entity = $this->em->getRepository('AppBundle:Task')->find($id);

        return $entity;
    }

    public function all()
    {
        $entity = $this->em->getRepository('AppBundle:Task')->findAll();
        return $entity;
    }

    public function post(array $parameters)
    {
        $entity = new Task();

        $parameters = $this->container->get('bikip_api.auxiliarFunctions.service')->addStudyToParameters($parameters);

        return $this->processForm($entity, $parameters, 'POST');
    }

    public function postCloneEntity(Task $taskCloned)
    {
        $taskCloned->unsetId();
        $this->em->persist($taskCloned);
        $statusCode = Response::HTTP_CREATED;
        return $this->helperService->buildResponseSuccessMessage($taskCloned, $statusCode);

    }

    public function patch(Task $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }


    public function delete(Task $entity)
    {
        $entityCloned = clone $entity;
        $this->em->remove($entity);
        $this->em->flush($entity);

        return $entityCloned;
    }

    public function deleteRecursive(Task $entity)
    {
        $parent = ($entity->getParent())? $entity->getParent() : $entity;

        $entities = $this->em->getRepository('AppBundle:Task')->findBy(array('parent' => $parent));

        if(!is_null($entities))
        {
            foreach ($entities as $item) {
                $this->em->remove($item);
            }
        }

        $parent = $this->em->getRepository('AppBundle:Task')->findOneBy(array('id' => $parent->getId()));
        $this->em->remove($parent);
        $this->em->flush();
    }

    public function getTaskUser(User $entity)
    {
        $entities = $this->em->getRepository('AppBundle:Task')->findBy(array('userAssigned' => $entity->getId()));  //Esto hacerlo en algun momento del otro lado, para optimizar
        return $entities;
    }


    private function processForm(Task $entity, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(TaskType::class, $entity, array('method' => $method));

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