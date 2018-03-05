<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 02/01/18
 * Time: 12:13
 */

namespace AppBundle\Controller;

use ApiBundle\ApiBundle;
use ApiBundle\Controller\BaseApiController;
use ApiBundle\Entity\User;
use AppBundle\Handler\TaskHandler;
use function Clue\StreamFilter\remove;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Task;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



/**
 * @Route("/api/v1")
 * @NamePrefix()
 * @Prefix("v1")
 */
class TaskController extends BaseApiController implements IAppController
{



    /**
     * Get a single Task for the given id
     *
     * @Get("/tasks/{id}.{_format}", defaults={"_format" = "json"}, name="api_get_task")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Tasks",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     * @param $id
     * @return View
     */
    public function getTaskAction($id)
    {
        $entity = $this->get('bikip_api.task.handler')->get($id);

        return $this->view($entity, Response::HTTP_OK)->setContext($this->context);
    }

    /**
     * Get a all Task for a user
     *
     * @Get("/users/{id}/tasks", defaults={"_format" = "json"}, name="api_get_user_tasks")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Tasks",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */

    public function getTasksUserAction(User $user)
    {
        $data = $this->get('bikip_api.task.handler')->getTaskUser($user);

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Get a all Task
     *
     * @Get("/tasks.{_format}", defaults={"_format" = "json"}, name="api_get_tasks")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Tasks",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @return View
     */
    public function getTasksAction()
    {
        $data = $this->get('bikip_api.task.handler')->all();

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Create a new Task from the submitted data
     *
     * @Post("/tasks.{_format}", defaults={"_format" = "json"}, name="api_post_task")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Tasks",
     *   input="AppBundle\Form\TaskType",
     *   statusCodes = {
     *     201 = "Resource successfully created",
     *     400 = "Invalid data submitted",
     *     401 = "Unauthorized"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function postTaskAction(Request $request)
    {

        $data = $this->get('bikip_api.generateTask.service')->generateTask($request);
        return $this->view($data['message'], $data['status']);
    }

    /**
     * Update a Task partially
     *
     * @Patch("/tasks/{id}.{_format}", defaults={"_format" = "json"}, name="api_patch_task")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Tasks",
     *   statusCodes = {
     *     204 = "Resource successfully edited",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     *

     *
     * @return View
     */
    public function patchTaskAction(Request $request, Task $entity)
    {
        $data = $this->get('bikip_api.task.handler')->patch(
            $entity,
            json_decode($request->getContent(), true)
        );

        return $this->view($data['message'], $data['status']);
    }

    /**
     * Deletes a Task given the id
     *
     * @Delete("/tasks/{id}.{_format}", defaults={"_format" = "json"}, name="api_delete_task")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Tasks",
     *   statusCodes = {
     *     204 = "Resource successfully deleted",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     *
     *
     * @return View
     */
    public function deleteTaskAction(Task $entity)
    {
        $data = $this->get('bikip_api.task.handler')->delete($entity);

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Deletes All task with same parent. Parent included
     *
     * @Delete("/tasks/{id}/tasks", defaults={"_format" = "json"}, name="api_delete_task_recursive")
     *
     * @ApiDoc(
     *   resource = true,
     *   section = "Tasks",
     *   statusCodes = {
     *     204 = "Resource successfully deleted",
     *     401 = "Unauthorized",
     *     404 = "Resource not found"
     *   }
     * )
     *
     *
     * @return View
     */
    public function deleteTaskRecursiveAction(Task $entity)
    {
        $this->get('bikip_api.task.handler')->deleteRecursive($entity);

        return $this->view('', Response::HTTP_NO_CONTENT);
    }






}