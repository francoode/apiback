<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 08/01/18
 * Time: 12:54
 */

namespace AppBundle\Controller;

use ApiBundle\Controller\BaseApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Post;
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
class OnboardingController extends BaseApiController implements IOnboardingController
{
    /**
     * Create the required study
     *
     * @Post("/onboardings/study.{_format}", defaults={"_format" = "json"}, name="api_post_onboardings")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Onboarding",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when bad request",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function postOnboardingsStudyAction(Request $request)
    {
        $data = $this->get('bikip_api.study.handler')->post(
            json_decode($request->getContent(), true) ?? []
        );

        $this->context->setGroups(['basic_onboarding']);

        return $this->view($data['message'], $data['status'])->setContext($this->context);
    }

    /**
     * Get study for onboarding process
     *
     * @Get("/onboardings/study.{_format}", defaults={"_format" = "json"}, name="api_get_onboardings")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Onboarding",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     204 = "Returned when no content",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function getOnboardingsStudysAction()
    {
        $studies = $this->get('bikip_api.onboarding.handler')->all();

        if (null == $studies) {
            return $this->view($studies, Response::HTTP_NO_CONTENT);
        }

        return $this->view($studies, Response::HTTP_OK);
    }
}