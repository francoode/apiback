<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 03/01/18
 * Time: 13:55
 */

namespace AppBundle\Handler;

use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\UserExtends;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\UserExtendsType;
use ApiBundle\Entity\User;

class UserExtendsHandler extends EntitiesService
{
    public function get($id)
    {

        $entity = $this->em->getRepository('ApiBundle:User')->find($id);

        return $entity;
    }

    public function all()
    {
        $entity = $this->em->getRepository('ApiBundle:User')->findAll();
        return $entity;
    }

    public function post(array $parameters)
    {
        $entity = new UserExtends();
        return $this->processForm($entity, $parameters, 'POST');
    }

    public function patch(UserExtends $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }


    public function delete(UserExtends $entity)
    {
        $userExtends = $this->em->getRepository('AppBundle:UserExtends')->findOneBy(
            array('idUser' => $entity->getId())
        );

        $entityCloned = clone $entity;
        $this->em->remove($userExtends);
        $this->em->remove($entity);
        $this->em->flush($entity);

        return $entityCloned;
    }

    private function processForm(UserExtends $entity, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(UserExtendsType::class, $entity, array('method' => $method));

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