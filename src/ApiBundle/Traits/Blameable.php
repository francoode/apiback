<?php

namespace ApiBundle\Traits;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

trait Blameable
{
    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(referencedColumnName="id")
     * @Gedmo\Blameable(on="create")
     * @Serializer\Groups({"created_by", "default_blameable"})
     */
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(referencedColumnName="id")
     * @Gedmo\Blameable(on="update")
     * @Serializer\Groups({"modified_by", "default_blameable"})
     */
    private $modifiedBy;

    /**
     * Get modifyBy
     *
     * @return \ApiBundle\Entity\User
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Get createBy
     *
     * @return \ApiBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
    }
}
