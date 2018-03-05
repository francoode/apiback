<?php
namespace ApiBundle\Handler;

use ApiBundle\Entity\Equipment;
use ApiBundle\Entity\Model;
use ApiBundle\Entity\Productor;
use ApiBundle\Form\EquipmentType;
use ApiBundle\Service\EntitiesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EquipmentHandler extends EntitiesService
{

    /**
     * @param \ApiBundle\Entity\Productor $productor
     * @param \ApiBundle\Entity\Equipment $equipment
     *
     * @return mixed
     */
    public function get(Productor $productor, Equipment $equipment)
    {
        $equipment = $this->findEquipmentOfProductor($equipment->getId(), $productor);

        return $equipment;
    }

    /**
     * @param \ApiBundle\Entity\Productor $productor
     *
     * @return array
     */
    public function all(Productor $productor)
    {
        $items = $productor->getEquipments()->getValues();
        if (!$items) {
            throw new NotFoundHttpException('No hay datos cargados', null, 404);
        }

        return $items;
    }

    /**
     * Create a new entity
     *
     * @param  Productor $productor
     * @param  Request   $request
     *
     * @return array
     */
    public function post(Productor $productor, Request $request)
    {
        return $this->processPostForm($productor, $request);
    }

    /**
     * Edit an entity
     *
     * @param  Productor $productor
     * @param  Request   $request
     *
     * @return array
     */
    public function patchEquipments(Productor $productor, Request $request)
    {
        return $this->processPatchEquipments($productor, $request);
    }

    /**
     * Update a entity partially
     *
     * @param  Equipment $equipment
     * @param  Request   $request
     * @param  Productor $productor
     *
     * @return array
     */
    public function patch(Equipment $equipment, Request $request, Productor $productor)
    {
        $equipment = $this->findEquipmentOfProductor($equipment->getId(), $productor);

        return $this->processPatchForm($equipment, $request);
    }

    /**
     * Delete the entity given the identifier
     *
     * @param  Equipment $equipment
     * @param  Productor $productor
     */
    public function delete(Equipment $equipment, Productor $productor)
    {
        $equipment = $this->findEquipmentOfProductor($equipment->getId(), $productor);

        $this->em->remove($equipment);
        $this->em->flush($equipment);
    }

    /**
     * @param \ApiBundle\Entity\Productor               $productor
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    private function processPostForm(Productor $productor, Request $request)
    {
        try {
            foreach ($request->request->get('equipments') as $equipment) {
                $this->generateEquipment($equipment, $productor);
            }
            $this->em->flush();
            $equipments = $productor->getEquipments();

            return $this->helperService->buildResponseSuccessMessage($equipments, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($e->getCode()) {
                $code = $e->getCode();
            }
            $msg = $e->getMessage();

            return $this->helperService->buildResponseErrorMessage("Error al registrar el parque de Maquinaria. $msg", $code);
        }
    }

    /**
     * @param \ApiBundle\Entity\Equipment               $equipment
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    private function processPatchForm(Equipment $equipment, Request $request)
    {
        // create form
        $form = $this->formFactory->create(EquipmentType::class, $equipment, array('method' => 'PATCH'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->em->flush();

            return $this->helperService->buildResponseSuccessMessage('ok', Response::HTTP_NO_CONTENT);
        }

        return [
            'message' => $this->container->get('kodear_api.form_errors_service')->getFormErrors($form),
            'status' => Response::HTTP_BAD_REQUEST,
        ];
    }

    /**
     * @param \ApiBundle\Entity\Productor               $productor
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    private function processPatchEquipments(Productor $productor, Request $request)
    {
        try {
            foreach ($request->request->get('equipments') as $equipment) {
                $this->updateEquipment($equipment, $productor);
            }
            $this->em->flush();

            return $this->helperService->buildResponseSuccessMessage('Se actualizó el parque de Maquinaria', Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($e->getCode()) {
                $code = $e->getCode();
            }
            $msg = $e->getMessage();

            return $this->helperService->buildResponseErrorMessage("Error al actualizar el parque de Maquinaria. $msg", $code);
        }
    }

    /**
     * @param array                       $equipment
     * @param \ApiBundle\Entity\Productor $productor
     */
    private function generateEquipment(array $equipment, Productor $productor)
    {
        if (!isset($equipment['model'])) {
            throw new BadRequestHttpException('Los campos ingresados no son válidos', null, Response::HTTP_BAD_REQUEST);
        }

        $model = $this->generateModel($equipment['model']);

        $quantity = isset($equipment['quantity']) ? $equipment['quantity'] : 1;
        $this->replaceEquipments($productor, $model);

        for ($i = 0; $i < $quantity; $i++) {
            $equipment = new Equipment();
            $equipment
                ->setModel($model)
                ->setProductor($productor);

            $productor->addEquipment($equipment);

            $this->em->persist($equipment);
            $this->em->persist($productor);
        }
    }

    /**
     * @param array                       $equipmentRequest
     * @param \ApiBundle\Entity\Productor $productor
     */
    private function updateEquipment(array $equipmentRequest, Productor $productor)
    {
        /** @var Equipment $equipment */
        if (!isset($equipmentRequest['id'])) {
            throw new BadRequestHttpException("Se requiere el id de Equipment", null, Response::HTTP_BAD_REQUEST);
        }
//        if (!isset($equipmentRequest['hours'])) {
//            throw new BadRequestHttpException('Se debe cargar las hs/kms de la maquinaria', null, Response::HTTP_BAD_REQUEST);
//        }
//        if (!isset($equipmentRequest['year'])) {
//            throw new BadRequestHttpException('Se debe cargar los años de la maquinaria', null, Response::HTTP_BAD_REQUEST);
//        }
        $equipment = $this->findEquipmentOfProductor($equipmentRequest['id'], $productor);
        if ($equipment) {
            $equipment
                ->setDetail(isset($equipmentRequest['detail']) ? $equipmentRequest['detail'] : null)
                ->setHours(isset($equipmentRequest['hours']) ? $equipmentRequest['hours'] : null)
                ->setYear(isset($equipmentRequest['year']) ? $equipmentRequest['year'] : null);

            $this->em->persist($equipment);
        }
    }

    /**
     * @param array $modelRequest
     *
     * @return \ApiBundle\Entity\Model
     */
    private function generateModel(array $modelRequest)
    {
        if (isset($modelRequest['id'])) {
            // model ya existe
            $model = $this->em->getRepository('ApiBundle:Model')->findOneBy(['id' => $modelRequest['id']]);
        } else {
            if (!isset($modelRequest['brand']) ||
                !isset($modelRequest['machinery']) ||
                !isset($modelRequest['description'])) {
                throw new BadRequestHttpException('Los campos ingresados no son válidos', null, Response::HTTP_BAD_REQUEST);
            }

            $model = new Model();
            $brand = $this->em->getRepository('ApiBundle:Brand')->findOneBy(['id' => $modelRequest['brand']]);
            if (!$brand) {
                throw new NotFoundHttpException('Marca inexistente', null, 404);
            }
            $machinery = $this->em->getRepository('ApiBundle:Machinery')->findOneBy(['id' => $modelRequest['machinery']]);
            if (!$machinery) {
                throw new NotFoundHttpException('Maquinaria inexistente', null, 404);
            }

            $model->setBrand($brand);
            $model->setMachinery($machinery);
            $model->setDescription($modelRequest['description']);
            $model->setOther(true);
            $this->em->persist($model);
        }

        if (!$model) {
            throw new NotFoundHttpException('Modelo inexistente', null, 404);
        }

        return $model;
    }

    /**
     * @param                             $id
     * @param \ApiBundle\Entity\Productor $productor
     *
     * @return \ApiBundle\Entity\Equipment|null|object
     */
    private function findEquipmentOfProductor($id, Productor $productor)
    {
        return $this->em->getRepository('ApiBundle:Equipment')->findOneBy(['id' => $id, 'productor' => $productor]);
    }

    private function replaceEquipments(Productor $productor, Model $model)
    {
        $equipments = $this->em->getRepository('ApiBundle:Equipment')->findBy(['productor' => $productor, 'model' => $model]);
        foreach ($equipments as $equipment) {
            $this->em->remove($equipment);
        }
        $this->em->flush();
    }
}
