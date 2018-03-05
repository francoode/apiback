<?php

namespace ApiBundle\Traits;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation\Exclude;
use Gedmo\Mapping\Annotation as Gedmo;

trait SoftDeletes
{
    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     * @Exclude()
     * @var \DateTime
     */
    private $deletedAt;

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    public function isDeleted()
    {
        $now = new \DateTime();
        return ($this->deletedAt != null) && ($now > $this->deletedAt);
    }
}
