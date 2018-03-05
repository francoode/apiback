<?php

namespace ApiBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ModelsFilter extends SQLFilter
{
    /**
     * Filtra los models con other = false
     *
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->getReflectionClass()->name != 'ApiBundle\Entity\Model') {
            return '';
        }

        return sprintf('%s.other = 0', $targetTableAlias);
    }
}
