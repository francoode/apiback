<?php
/**
 * Copyright (c) 2015 - Kodear
 */

namespace ApiSecurityBundle\Util;


use Symfony\Component\HttpFoundation\Request;

interface ApiKeyExtractor
{
    public function extract(Request $request);
}
