<?php
namespace ApiBundle\Handler;


use ApiBundle\Entity\Production;
use ApiBundle\Form\ProductionType;
use ApiBundle\Service\EntitiesService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductionHandler extends EntitiesService
{

    /**
     * Get an entity given the identifier
     *
     * @param  mixed $id The identifier
     *
     * @return mixed            The entity identified by identifier
     */
    public function get($id)
    {
        // TODO: Implement get() method.
    }

    /**
     * Get a list of entities
     *
     * @return array            List of entities
     */
    public function all()
    {
        $items = $this->em->getRepository('ApiBundle:Production')->findAll();
        if (!$items) {
            throw new NotFoundHttpException('No hay datos cargados.');
        }

        return $items;
    }

    /**
     * Create a new entity
     *
     * @param  array $parameters Parameters to create the entity
     *
     * @return array
     */
    public function post(array $parameters)
    {
        $production = new Production();

        return $this->processForm($production, $parameters, 'POST');
    }

    /**
     * Edit an entity
     *
     * @param  Entity $entity     Entity to edit or create
     * @param  array  $parameters Parameters to edit or create the entity
     *
     * @return Entity                   The edited or create entity
     */
    public function put($entity, array $parameters)
    {
        // TODO: Implement put() method.
    }

    /**
     * Update a entity partially
     *
     * @param  Production $production
     * @param  array  $parameters Parameters to update the entity
     *
     * @return Entity                   The updated entity
     */
    public function patch(Production $production, array $parameters)
    {
        return $this->processForm($production, $parameters, 'PATCH');
    }

    /**
     * Delete the entity given the identifier
     *
     * @param  Production $production
     *
     * @return Entity           The deleted entity
     */
    public function delete(Production $production)
    {
        $this->em->remove($production);
        $this->em->flush($production);
    }

    private function processForm(Production $production, array $parameters, $method = 'PUT')
    {
        // create form
        $form = $this->formFactory->create(ProductionType::class, $production, array('method' => $method));

        // submit parameters on the form
        $form->submit($parameters, 'PATCH' !== $method);

        // validate form
        if ($form->isValid()) {
            $production = $form->getData();

            $this->em->persist($production);
            $this->em->flush($production);

            return $this->helperService->buildResponseSuccessMessage($production, Response::HTTP_CREATED);
        }

        return [
            'message' => $this->container->get('kodear_api.form_errors_service')->getFormErrors($form),
            'status' => Response::HTTP_BAD_REQUEST
        ];
    }
}
