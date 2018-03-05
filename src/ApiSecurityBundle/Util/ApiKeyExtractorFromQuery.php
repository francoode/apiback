<?php
/**
 * Copyright (c) 2015 - Kodear
 */

namespace ApiSecurityBundle\Util;

use Symfony\Component\HttpFoundation\Request;

class ApiKeyExtractorFromQuery implements ApiKeyExtractor
{
    private $parameterName;

    public function __construct($parameterName)
    {
        $this->parameterName = $parameterName;
    }

    public function extract(Request $request)
    {
        return $request->get($this->parameterName);
    }
}
