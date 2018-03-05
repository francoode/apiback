<?php

namespace ApiBundle\Serializer;
use JMS\Serializer\Metadata\PropertyMetadata;

class CamelCaseNamingStrategy extends \JMS\Serializer\Naming\CamelCaseNamingStrategy
{
    public function translateName(PropertyMetadata $property)
    {
        return lcfirst(parent::translateName($property));
    }
}
