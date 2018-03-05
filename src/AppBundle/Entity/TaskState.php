<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskState
 *
 * @ORM\Table(name="task_state")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskStateRepository")
 */
class TaskState
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="isTerminal", type="boolean")
     */
    private $isTerminal;

    /**
     * @var bool
     *
     * @ORM\Column(name="isInitial", type="boolean")
     */
    private $isInitial;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * Set isTerminal
     *
     * @param boolean $isTerminal
     *
     * @return TaskState
     */
    public function setIsTerminal($isTerminal)
    {
        $this->isTerminal = $isTerminal;

        return $this;
    }

    /**
     * Get isTerminal
     *
     * @return bool
     */
    public function getIsTerminal()
    {
        return $this->isTerminal;
    }

    /**
     * Set isInitial
     *
     * @param boolean $isInitial
     *
     * @return TaskState
     */
    public function setIsInitial($isInitial)
    {
        $this->isInitial = $isInitial;

        return $this;
    }

    /**
     * Get isInitial
     *
     * @return bool
     */
    public function getIsInitial()
    {
        return $this->isInitial;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return TaskState
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

    public function __toString()
    {
        return $this->name;
    }
}

