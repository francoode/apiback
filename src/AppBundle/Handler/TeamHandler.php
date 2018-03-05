<?php
namespace AppBundle\Handler;
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 13:46
 */
use ApiBundle\Service\EntitiesService;
use AppBundle\Entity\Team;
use AppBundle\Entity\UserExtends;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\TeamType;

class TeamHandler extends EntitiesService
{


    public function get($id)
    {

        $entity = $this->em->getRepository('AppBundle:Team')->find($id);

        return $entity;
    }

    public function all()
    {
        $entity = $this->em->getRepository('AppBundle:Team')->findAll();
        return $entity;
    }

    public function post(array $parameters)
    {
        $entity = new Team();
        return $this->processForm($entity, $parameters, 'POST');
    }

    public function patch(Team $entity, array $parameters)
    {
        return $this->processForm($entity, $parameters, 'PATCH');
    }


    public function delete(Team $entity)
    {
        $this->em->remove($entity);
        $this->em->flush($entity);
    }

    public function getTeamUsers(Team $entity)
    {
        $data = $this->em->getRepository('AppBundle:UserExtends')->findBy(array('idTeam' => $entity->getId()));
        return $data;
    }

    private function processForm(Team $entity, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(TeamType::class, $entity, array('method' => $method));

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

    public function addUserExtends($idUser,Team $idTeams)
    {
        $userExtends = $this->em->getRepository('AppBundle:UserExtends')->findOneBy(array('idUser' => $idUser));
        $idTeams->addUserExtendsCollection($userExtends);
        $this->em->flush($idTeams);
    }

    public function removeUserExtends($idUser,Team $idTeams)
    {
        $userExtends = $this->em->getRepository('AppBundle:UserExtends')->findOneBy(array('idUser' => $idUser));
        $idTeams->removeUserExtendsCollection($userExtends);
        $this->em->flush($idTeams);
    }


}