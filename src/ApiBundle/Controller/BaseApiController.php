<?php
namespace ApiBundle\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;

class BaseApiController extends FOSRestController
{
    /** @var \FOS\RestBundle\Context\Context  */
    protected $context;

    public function __construct()
    {
        $this->context = new Context();
    }

    protected function commonResponse($data)
    {
        return $this->view($data['message'], $data['status']);
    }

    protected function getForm($formType, $entity = null, $method = "POST")
    {
        return $this->createForm(
            $formType,
            $entity,
            array(
                'method' => $method,
            )
        );
    }
}
