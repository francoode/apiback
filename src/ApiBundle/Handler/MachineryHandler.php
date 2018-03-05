<?php

namespace ApiBundle\Handler;

use ApiBundle\Entity\Brand;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ApiBundle\Entity\Machinery;

class MachineryHandler
{
    const METHOD_GET = 'get';
    const METHOD_ALL = 'all';
    const METHOD_POST = 'post';
    const METHOD_PATCH = 'patch';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';

    private $om;
    private $repository;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;

        $this->repository = $this->om->getRepository("ApiBundle:Machinery");
    }

    /**
     * Get the machinery identified by given identifier
     *
     * @param integer $id Machinery identifier
     * @return Machinery The machinery identified by given identifier
     */
    public function get($id)
    {
        // validate identifier
        if ($this->validateParameters($this::METHOD_GET, array('id' => $id))) {
            // get the item indetified by $id
            $item = $this->repository->findOneById($id);

            // check if the item exists
            if (!$item) {
                throw new NotFoundHttpException('Machinery not found.');
            }
        } else {
            throw new BadRequestHttpException('Request not well formed.');
        }

        return $item;
    }

    /**
     * Get all the machineries
     *
     * @return array Collection of machineries
     */
    public function all()
    {
        $items = $this->repository->findAll();

        if (!$items) {
            throw new NotFoundHttpException('No hay datos cargados.');
        }

        return $items;
    }

    public function getMachineriesBrands(Machinery $machinery)
    {
        $items = $machinery->getBrands();

        if (!$items) {
            throw new NotFoundHttpException('No hay datos cargados.');
        }

        return $items;
    }

    public function getMachineriesBrandsModels(Machinery $machinery, Brand $brand)
    {
        $this->om->getFilters()->enable('modelsfilter');
        $items = $this->om->getRepository('ApiBundle:Model')->findAllModelsOfMachinery($machinery, $brand);

        if (!$items) {
            throw new NotFoundHttpException('No hay datos cargados.');
        }

        return $items;
    }

    /**
     * Create a new machinery
     *
     * @param array $parameters Parameters to create the machinery
     *
     * @return Machinery The created machinery
     */
    public function post(array $parameters)
    {
        return array();
    }

    /**
     * Update a machinery completly
     *
     * @param Machinery $machinery Machinery to update
     * @param  array  $parameters Parameters to update the machinery
     *
     * @return Machinery The updated machinery
     */
    public function put($machinery, array $parameters)
    {
        return array();
    }

    /**
     * Update a machinery partially
     *
     * @param Machinery $machinery The machinery to update
     * @param array  $parameters Parameters to update the machinery
     *
     * @return Machinery The updated machinery
     */
    public function patch($machinery, array $parameters)
    {
        return array();
    }

    /**
     * Delete the machinery indentified by given the identifier
     * @param integer $id Machinery identifier
     *
     * @return Machinery The deleted machinery
     */
    public function delete($id)
    {
        return array();
    }

    /**
     * Validate parameters for the method given
     *
     * @param $method string The method to validate
     * @param $parameters array Collection of parameters to validate
     *
     * @return boolean True if the parameters given are valid or False if are not valid
     */
    private function validateParameters($method, $parameters)
    {
        $result = true;

        switch ($method) {
            case $this::METHOD_GET:
                if (!array_key_exists('id', $parameters)) {
                    $result = false;
                } else if (!is_numeric($parameters['id'])) {
                    $result = false;
                }
                break;

            default:
                $result = false;
                break;
        }

        return $result;
    }
}
