<?php
namespace ApiBundle\Handler;

use ApiBundle\Entity\ProductionProductor;
use ApiBundle\Entity\ProductionRange;
use ApiBundle\Entity\Productor;
use ApiBundle\Service\EntitiesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductionProductorHandler extends EntitiesService
{
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
     * @param \ApiBundle\Entity\Productor $productor
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    private function processPostForm(Productor $productor, Request $request)
    {
        $productionRangeIds = $request->request->get('productionRanges');

        foreach ($productionRangeIds as $productionRangeId) {
            $productionRange = $this->em->getRepository('ApiBundle:ProductionRange')->findOneBy(
                ['id' => $productionRangeId]
            );
            if (!$productionRange) {
                throw new NotFoundHttpException("Rango de Producción inexistente. $productionRangeId", null, Response::HTTP_NOT_FOUND);
            }

            if ($this->exists($productor, $productionRange)) {
                // actualizar la ya cargada.
                $productionProductor = $this->exists($productor, $productionRange);
            } else {
                $productionProductor = new ProductionProductor();
            }

            $productionProductor
                ->setProductionRange($productionRange)
                ->setProductor($productor);
            $this->em->persist($productionProductor);
        }
        try {
            $this->em->flush();

            return $this->helperService->buildResponseSuccessMessage("Se registró la Producción del Productor", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $msg = $e->getMessage();

            return $this->helperService->buildResponseErrorMessage(
                "Error al registrar la Producción. $msg",
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function exists(Productor $productor, ProductionRange $productionRange)
    {
        return $this->em->getRepository('ApiBundle:ProductionProductor')->findProductionRangeByProduction($productionRange, $productor);
    }
}
