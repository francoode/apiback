<?php

namespace ApiBundle\Handler;

interface HandlerInterface
{
    /**
     * Get an entity given the identifier
     * @param  mixed    $id     The identifier
     * @return mixed            The entity identified by identifier
     */
    public function get($id);

    /**
     * Get a list of entities
     * @return array            List of entities
     */
    public function all();

    /**
     * Create a new entity
     * @param  array    $parameters     Parameters to create the entity
     * @return Entity                   The created entity
     */
    public function post(array $parameters);

    /**
     * Edit an entity
     * @param  Entity   $entity         Entity to edit or create
     * @param  array    $parameters     Parameters to edit or create the entity
     * @return Entity                   The edited or create entity
     */
    public function put($entity, array $parameters);

    /**
     * Update a entity partially
     * @param  Entity   $entity         The entity to update
     * @param  array    $parameters     Parameters to update the entity
     * @return Entity                   The updated entity
     */
    public function patch($entity, array $parameters);

    /**
     * Delete the entity given the identifier
     * @param  mixed    $id     The given identifier
     * @return Entity           The deleted entity
     */
    public function delete($id);
}
