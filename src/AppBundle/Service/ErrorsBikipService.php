<?php
/**
 * Created by PhpStorm.
 * User: franco
 * Date: 10/01/18
 * Time: 11:00
 */
namespace AppBundle\Service;
use Symfony\Component\HttpFoundation\Response;

class ErrorsBikipService
{

    public function duplicateEntity()
    {

        return [
            'error' => [
                'code' => 422,
                'message' => 'The entity already exists',
            ],
        ];
    }

    public function errorCatch($msg)
    {
        return [
            'error' => [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $msg
            ]
        ];
    }
}