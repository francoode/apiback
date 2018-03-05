<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 8/10/15
 * Time: 8:36 PM
 */

namespace ApiSecurityBundle\Util;
use Ramsey\Uuid\Uuid;

/**
 * Generador de keys para la utilización de la API.
 */
class ApiKeyGenerator
{
    public function generate()
    {
        return Uuid::uuid4()->toString();
    }
}
