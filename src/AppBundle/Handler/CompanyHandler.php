<?php
namespace AppBundle\Handler;
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 13:46
 */
use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\Company;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\CompanyType;

class CompanyHandler extends EntitiesService
{


    public function get($id)
    {

        $entity = $this->em->getRepository('AppBundle:Company')->find($id);

        return $entity;
    }

    public function all()
    {
        $entity = $this->em->getRepository('AppBundle:Company')->findAll();
        return $entity;
    }

    public function post(array $parameters)
    {
        $entity = new Company();

        $parameters = $this->container->get('bikip_api.auxiliarFunctions.service')->addStudyToParameters($parameters);

        return $this->processForm($entity, $parameters, 'POST');
    }

    public function patch(Company $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }


    public function delete(Company $entity)
    {
        $entityCloned = clone $entity;
        $this->em->remove($entity);
        $this->em->flush($entity);

        return $entityCloned;
    }

    public function getCompanyUsers(Company $entity)
    {
        $data = $this->em->getRepository('AppBundle:UserExtends')->findBy(array('idCompany' => $entity->getId()));
        return $data;
    }

    private function processForm(Company $entity, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(CompanyType::class, $entity, array('method' => $method));

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