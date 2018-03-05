<?php
/**
 * Copyright (c) 2015 - Kodear
 */

namespace ApiSecurityBundle\Util;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class ApiKeyExtractorFromHeader implements ApiKeyExtractor
{
    private $parameterName;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct($parameterName, LoggerInterface $logger)
    {
        $this->parameterName = $parameterName;
        $this->logger = $logger;
        $this->logger->debug(sprintf('Parámetro para extracción del API KEY : %s', $parameterName));
    }

    public function extract(Request $request)
    {
        return $request->headers->get($this->parameterName);
    }
}
