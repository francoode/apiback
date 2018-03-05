<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 08/01/18
 * Time: 12:52
 */

namespace AppBundle\Handler;


use ApiBundle\Service\EntitiesService;
use JMS\Serializer;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BootstrappingHandler extends EntitiesService
{
    private $serializer;

    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    public function all()
    {
        $data = array();

        //get study and user
        $user = $this->helperService->requester;

        // initialize data
        $data['data'] = array();

        //set data in array
        $data['data']['users'] = $this->getUser($user);
        $data['data']['studies'] = $this->getStudy($user);
        $data['data']['contacts'] = $this->getContacts($data['data']['studies']['id']);
        $data['data']['companies'] = $this->getCompanies($data['data']['studies']['id']);
        $data['data']['business'] = $this->getBusiness($data['data']['studies']['id']);
        $data['data']['notes'] = $this->getNotes($data['data']['studies']['id']);
        $data['data']['logaction'] = $this->getLogAction($data['data']['studies']['id']);
        $data['data']['usersstudy'] = $this->getStudyUsers($data['data']['studies']['id']);


        //set catalogues
        $data['catalogues'] = array();
        $data['catalogues']['companyTypes'] = $this->getCompanyTypes();
        $data['catalogues']['pipelines'] = $this->getPipelines();
        $data['catalogues']['pipelinesstates'] = $this->getPipeLineState();
        $data['catalogues']['logManualType'] = $this->getManualType();


        return $data;
    }

    private function getUser($user)
    {

        // get serialized user
        $userJson = $this->serializer->serialize(
            $user,
            'json'
        );

        // get user array
        $userArr = null != json_decode($userJson, true) ? json_decode($userJson, true) : array();

        return $userArr = (count($userArr) > 0)? $userArr : null;

    }

    private function getStudy($user)
    {
        // get user extends
        $userExtends = $this
            ->em
            ->getRepository('AppBundle:UserExtends')
            ->findOneBy(array('idUser' => $user->getId()))
        ;

        //Esto se repite 50 veces, lo ideal seria crear un metodo aparte... pero bueno.
        $userExtendsJson = $this->serializer->serialize(
            $userExtends,
            'json'
        );

        // get study array
        $studyArr = null != json_decode($userExtendsJson, true) ?
            (
            array_key_exists('study', json_decode($userExtendsJson, true)) ?
                json_decode($userExtendsJson, true)['study'] :
                array()
            ) :
            array()
            ;

        // add study
        return $studyArr = (count($studyArr))? $studyArr : null;

    }

    private function getCompanyTypes()
    {
        $companyTypes = $this
            ->em
            ->getRepository('AppBundle:CompanyType')
            ->findAll()
        ;

        $data = [];

        foreach ($companyTypes as $companyType) {
            // get serialized company type
            $companyTypeJson = $this->serializer->serialize(
                $companyType,
                'json'
            );



            $companyTypeArr =
                null != json_decode($companyTypeJson, true) ?
                    json_decode($companyTypeJson, true) :
                    array();

            $data[] = $companyTypeArr;
        }

        return $data = (count($data))? $data : null;

    }

    private function getContacts($study)
    {
        $contacts = $this
            ->em
            ->getRepository('AppBundle:Contact')
            ->findBy(array('study' => $study))
        ;


        $data = array();
        foreach ($contacts as $contact) {

            $contactJson = $this->serializer->serialize(
                $contact,
                'json'
            );


            $contactArr = null != json_decode($contactJson, true) ? json_decode($contactJson, true) : array();

            $data[] = $contactArr;
        }

        return $data = (count($data)>0)? $data : null;
    }

    private function getCompanies($study)
    {

        $companies = $this
            ->em
            ->getRepository('AppBundle:Company')
            ->findBy(array('study' => $study))
        ;


        $data = array();
        foreach ($companies as $company) {

            $companyJson = $this->serializer->serialize(
                $company,
                'json'
            );


            $companyArr = null != json_decode($companyJson, true) ? json_decode($companyJson, true) : array();

            $data[] = $companyArr;
        }

        return $data = (count($data)>0)? $data : null;
    }

    private function getBusiness($study)
    {
        $entities = $this
            ->em
            ->getRepository('AppBundle:Business')
            ->findBy(array('study' => $study))
        ;


        $data = array();
        foreach ($entities as $entity) {

            $entityJson = $this->serializer->serialize(
                $entity,
                'json'
            );


            $entityArr = null != json_decode($entityJson, true) ? json_decode($entityJson, true) : array();

            $data[] = $entityArr;
        }

        return $data = (count($data)>0)? $data : null;
    }

    private function getPipelines()
    {
        $entities = $this
            ->em
            ->getRepository('AppBundle:Pipeline')
            ->findAll()
        ;


        $data = array();
        foreach ($entities as $entity) {

            $entityJson = $this->serializer->serialize(
                $entity,
                'json'
            );


            $entityArr = null != json_decode($entityJson, true) ? json_decode($entityJson, true) : array();

            $data[] = $entityArr;
        }

        return $data = (count($data)>0)? $data : null;
    }

    private function getPipelineState()
    {

        $entities = $this
            ->em
            ->getRepository('AppBundle:PipelineState')
            ->findAll()
        ;


        $data = array();
        foreach ($entities as $entity) {
            // get serialized contact
            $entityJson = $this->serializer->serialize(
                $entity,
                'json'
            );


            $entityArr = null != json_decode($entityJson, true) ? json_decode($entityJson, true) : array();

            $data[] = $entityArr;
        }

        return $data = (count($data)>0)? $data : null;
    }

    private function getNotes($study)
    {

        $entities = $this
            ->em
            ->getRepository('AppBundle:Note')
            ->findBy(array('study' => $study))
        ;



        $data = array();
        foreach ($entities as $entity) {

            $entityJson = $this->serializer->serialize(
                $entity,
                'json'
            );


            $entityArr = null != json_decode($entityJson, true) ? json_decode($entityJson, true) : array();

            $data[] = $entityArr;
        }

        return $data = (count($data)>0)? $data : null;

    }

    private function getLogAction($study)
    {
        $entities = $this
            ->em
            ->getRepository('AppBundle:LogAction')
            ->findBy(array('study' => $study))
        ;



        $data = array();
        foreach ($entities as $entity) {

            $entityJson = $this->serializer->serialize(
                $entity,
                'json'
            );


            $entityArr = null != json_decode($entityJson, true) ? json_decode($entityJson, true) : array();

            $data[] = $entityArr;
        }

        return $data = (count($data)>0)? $data : null;
    }

    private function getStudyUsers($study){


        $entities = [];

        $usersExtends = $this->em->getRepository('AppBundle:UserExtends')->findBy(array('idStudy' => $study));


        if(count($usersExtends)>1){
        foreach ($usersExtends as $u) {
            $entities[] = $u->getIdUser();
        }
        }else
        {
            $entities[] = $usersExtends[0]->getIduser();
        }

        $data = array();
        foreach ($entities as $entity)
        {
            $entityJson = $this->serializer->serialize(
                $entity,
                'json'
            );
            $entityArr = null != json_decode($entityJson, true) ? json_decode($entityJson, true) : array();

            $data[] = $entityArr;
        }

        return $data = (count($data)>0)? $data : null;
    }

    private function getManualType()
    {
        $entities = $this
            ->em
            ->getRepository('AppBundle:LogTypeManual')
            ->findAll()
        ;


        $data = array();
        foreach ($entities as $entity) {
            // get serialized contact
            $entityJson = $this->serializer->serialize(
                $entity,
                'json'
            );


            $entityArr = null != json_decode($entityJson, true) ? json_decode($entityJson, true) : array();

            $data[] = $entityArr;
        }

        return $data = (count($data)>0)? $data : null;
    }




}