<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * PipelineState
 *
 * @ORM\Table(name="pipeline_state")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PipelineStateRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class PipelineState
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     * @Serializer\SerializedName("id")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\SerializedName("name")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pipeline")
     * @ORM\JoinColumn(name="pipeline_id", referencedColumnName="id")
     */
    private $pipeline;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return PipelineState
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set pipeline
     *
     * @param \AppBundle\Entity\Pipeline $pipeline
     *
     * @return PipelineState
     */
    public function setPipeline(\AppBundle\Entity\Pipeline $pipeline = null)
    {
        $this->pipeline = $pipeline;

        return $this;
    }

    /**
     * Get pipeline
     *
     * @return \AppBundle\Entity\Pipeline
     */
    public function getPipeline()
    {
        return $this->pipeline;
    }

    public function __toString()
    {
        return $this->name;
    }
}
