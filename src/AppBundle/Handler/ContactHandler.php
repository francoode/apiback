<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 08/01/18
 * Time: 12:52
 */

namespace AppBundle\Handler;


use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\Contact;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\ContactType;
use AppBundle\Entity\Company;
use AppBundle\Repository\UserExtendsRepository;


class ContactHandler extends EntitiesService
{


    public function get($id)
    {

        $entity = $this->em->getRepository('AppBundle:Contact')->find($id);

        return $entity;
    }

    public function all()
    {
        $entity = $this->em->getRepository('AppBundle:Contact')->findAll();
        return $entity;
    }

    public function post(array $parameters)
    {

        $entity = new Contact();
        $parameters = $this->setUserStudy($parameters);   ///Get User and Study and set in parameters array.
        $existsContact = $this->em->getRepository('AppBundle:Contact')->checkExistsContact($parameters['study'], $parameters['email']);
        if($existsContact){
            return [
                'message' => $this->container->get('bikip_api.errors_service')->duplicateEntity(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }
        else{
            return $this->processForm($entity, $parameters, 'POST');
        }

    }

    public function patch(Contact $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }


    public function delete(Contact $entity)
    {
        $entityCloned = clone $entity;
        $this->em->remove($entity);
        $this->em->flush($entity);


        return $entityCloned;
    }

    public function addCompany(Contact $contact, Company $company)
    {
            $contact->addCompanyCollection($company);
            $this->em->flush($contact);
    }

    public function deleteCompany(Contact $contact, Company $company)
    {
            $contact->removeCompanyCollection($company);
            $this->em->flush($contact);
    }


    private function processForm(Contact $entity, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(ContactType::class, $entity, array('method' => $method));

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


    ///Extends functions
    private function setUserStudy($parameters)
    {
        $parameters['userOwner']  = $this->helperService->requester->getId();
        $parameters['study']= $this->em->getRepository('AppBundle:UserExtends')->getUserStudy($parameters['userOwner']);
        return $parameters;
    }

}