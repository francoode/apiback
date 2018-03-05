<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 08/01/18
 * Time: 12:54
 */

namespace AppBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use AppBundle\Handler\BootstrappingHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/api/v1")
 * @NamePrefix()
 * @Prefix("v1")
 */
class BootstrappingController extends BaseApiController implements IAppController
{
    /**
     * Get a all application bootstrapping required to operate normally
     *
     * @Get("/bootstrappings.{_format}", defaults={"_format" = "json"}, name="api_get_bootstrappings")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Bootstrapping",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function getBootstrappingsAction()
    {
        $bootstrapping = $this->get('bikip_api.bootstrapping.handler')->all();

        return $this->view($bootstrapping, Response::HTTP_OK);
    }
}