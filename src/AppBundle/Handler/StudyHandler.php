<?php
namespace AppBundle\Handler;
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 13:46
 */
use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\Study;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\StudyType;

class StudyHandler extends EntitiesService
{


    public function get($id)
    {

        $entity = $this->em->getRepository('AppBundle:Study')->find($id);

        return $entity;
    }

    public function all()
    {
        $entity = $this->em->getRepository('AppBundle:Study')->findAll();
        return $entity;
    }

    public function post(array $parameters)
    {
        $entity = new Study();
        return $this->processForm($entity, $parameters, 'POST');
    }

    public function patch(Study $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }


    public function delete(Study $entity)
    {
        $entityCloned = clone $entity;
        $this->em->remove($entity);
        $this->em->flush($entity);

        return $entityCloned;
    }

    public function getStudyUsers(Study $entity)
    {
        $data = $this->em->getRepository('AppBundle:UserExtends')->findBy(array('idStudy' => $entity->getId()));
        return $data;
    }

    private function processForm(Study $entity, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(StudyType::class, $entity, array('method' => $method));

        // submit parameters on the form
        $form->submit($parameters, 'PATCH' !== $method);

        // validate form
        if ($form->isValid()) {

            $entity = $form->getData();

            $this->em->persist($entity);
            $this->em->flush($entity);

            // get user extends
            $userExtends = $this
                ->em
                ->getRepository('AppBundle:UserExtends')
                ->findOneBy(array('idStudy' => $entity->getId()))
            ;

            if (null == $userExtends) {
                $userExtends = new \AppBundle\Entity\UserExtends();
                $userExtends->setIdStudy($entity);
                $userExtends->setIdUser($this->helperService->requester);

                $this->em->persist($userExtends);
                $this->em->flush();
            }

            return $this->helperService->buildResponseSuccessMessage($entity, Response::HTTP_CREATED);
        }

        return [
            'message' => $this->container->get('kodear_api.form_errors_service')->getFormErrors($form),
            'status' => Response::HTTP_BAD_REQUEST
        ];
    }

}