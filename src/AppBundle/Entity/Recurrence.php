<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recurrence
 *
 * @ORM\Table(name="recurrence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecurrenceRepository")
 */
class Recurrence
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
     * @var string
     *
     * @ORM\Column(name="typeRecurrence", type="string", length=255)
     */
    private $typeRecurrence;

    /**
     * @var int
     *
     * @ORM\Column(name="separation", type="integer")
     */
    private $separation;

    /**
     * @var int
     *
     * @ORM\Column(name="dayOfMonth", type="integer", nullable=true)
     */
    private $dayOfMonth;

    /**
     * @var int
     *
     * @ORM\Column(name="mounthOfYear", type="integer")
     */
    private $mounthOfYear;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDateRecurrent", type="datetime")
     */
    private $endDateRecurrent;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $userOwner;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Study")
     * @ORM\JoinColumn(name="study_id", referencedColumnName="id")
     */
    protected $study;


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
     * Set typeRecurrence
     *
     * @param string $typeRecurrence
     *
     * @return Recurrence
     */
    public function setTypeRecurrence($typeRecurrence)
    {
        $this->typeRecurrence = $typeRecurrence;

        return $this;
    }

    /**
     * Get typeRecurrence
     *
     * @return string
     */
    public function getTypeRecurrence()
    {
        return $this->typeRecurrence;
    }

    /**
     * Set separation
     *
     * @param integer $separation
     *
     * @return Recurrence
     */
    public function setSeparation($separation)
    {
        $this->separation = $separation;

        return $this;
    }

    /**
     * Get separation
     *
     * @return int
     */
    public function getSeparation()
    {
        return $this->separation;
    }

    /**
     * Set dayOfMonth
     *
     * @param integer $dayOfMonth
     *
     * @return Recurrence
     */
    public function setDayOfMonth($dayOfMonth)
    {
        $this->dayOfMonth = $dayOfMonth;

        return $this;
    }

    /**
     * Get dayOfMonth
     *
     * @return int
     */
    public function getDayOfMonth()
    {
        return $this->dayOfMonth;
    }

    /**
     * Set mounthOfYear
     *
     * @param integer $mounthOfYear
     *
     * @return Recurrence
     */
    public function setMounthOfYear($mounthOfYear)
    {
        $this->mounthOfYear = $mounthOfYear;

        return $this;
    }

    /**
     * Get mounthOfYear
     *
     * @return int
     */
    public function getMounthOfYear()
    {
        return $this->mounthOfYear;
    }

    /**
     * Set endDateRecurrent
     *
     * @param \DateTime $endDateRecurrent
     *
     * @return Recurrence
     */
    public function setEndDateRecurrent($endDateRecurrent)
    {
        $this->endDateRecurrent = $endDateRecurrent;

        return $this;
    }

    /**
     * Get endDateRecurrent
     *
     * @return \DateTime
     */
    public function getEndDateRecurrent()
    {
        return $this->endDateRecurrent;
    }

    /**
     * Set userOwner
     *
     * @param \ApiBundle\Entity\User $userOwner
     *
     * @return Recurrence
     */
    public function setUserOwner(\ApiBundle\Entity\User $userOwner = null)
    {
        $this->userOwner = $userOwner;

        return $this;
    }

    /**
     * Get userOwner
     *
     * @return \ApiBundle\Entity\User
     */
    public function getUserOwner()
    {
        return $this->userOwner;
    }

    /**
     * Set study
     *
     * @param \AppBundle\Entity\Study $study
     *
     * @return Recurrence
     */
    public function setStudy(\AppBundle\Entity\Study $study = null)
    {
        $this->study = $study;

        return $this;
    }

    /**
     * Get study
     *
     * @return \AppBundle\Entity\Study
     */
    public function getStudy()
    {
        return $this->study;
    }
}
