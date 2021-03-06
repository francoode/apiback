<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Task
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\SerializedName("title")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\SerializedName("description")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     * @Serializer\Expose()
     * @Serializer\SerializedName("startDate")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     * @Serializer\Expose()
     * @Serializer\SerializedName("endDate")
     */
    private $endDate;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TaskState")
     * @ORM\JoinColumn(name="state", referencedColumnName="id")
     */
    private $state;


    /**
     * @var bool
     *
     * @ORM\Column(name="isRecurrent", type="boolean")
     * @Serializer\Expose()
     * @Serializer\SerializedName("isRecurrent")
     *
     */
    private $isRecurrent;

    /**
     * @var int
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     * @Serializer\Expose()
     * @Serializer\SerializedName("parentId")
     */
    private $parentId;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User")
     * @ORM\JoinColumn(name="user_owner", referencedColumnName="id", nullable=true)
     */
    protected $userOwner;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Study")
     * @ORM\JoinColumn(name="study_id", referencedColumnName="id")
     */
    protected $study;

    /**
     * @var \DateTime
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User")
     * @ORM\JoinColumn(name="user_assigned", referencedColumnName="id")
     * @Serializer\Expose()
     * @Serializer\SerializedName("userAssigned")
     */
    private $userAssigned;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Business", inversedBy="noteCollection")
     * @ORM\JoinTable(name="task_business")
     */
    private $business;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Company", inversedBy="noteCollection")
     * @ORM\JoinTable(name="task_company")
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Contact", inversedBy="noteCollection")
     * @ORM\JoinTable(name="task_contact")
     */
    private $contact;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Task", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Task", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Recurrence")
     * @ORM\JoinColumn(name="recurrence_id", referencedColumnName="id", nullable=true)
     * @Serializer\Expose()
     * @Serializer\SerializedName("recurrence")
     */
    protected $recurrence;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->business = new \Doctrine\Common\Collections\ArrayCollection();
        $this->company = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contact = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Task
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Task
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Task
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set isRecurrent
     *
     * @param boolean $isRecurrent
     *
     * @return Task
     */
    public function setIsRecurrent($isRecurrent)
    {
        $this->isRecurrent = $isRecurrent;

        return $this;
    }

    /**
     * Get isRecurrent
     *
     * @return boolean
     */
    public function getIsRecurrent()
    {
        return $this->isRecurrent;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return Task
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Task
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set userOwner
     *
     * @param \ApiBundle\Entity\User $userOwner
     *
     * @return Task
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
     * @return Task
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

    /**
     * Set userAssigned
     *
     * @param \ApiBundle\Entity\User $userAssigned
     *
     * @return Task
     */
    public function setUserAssigned(\ApiBundle\Entity\User $userAssigned = null)
    {
        $this->userAssigned = $userAssigned;

        return $this;
    }

    /**
     * Get userAssigned
     *
     * @return \ApiBundle\Entity\User
     */
    public function getUserAssigned()
    {
        return $this->userAssigned;
    }

    /**
     * Add business
     *
     * @param \AppBundle\Entity\Business $business
     *
     * @return Task
     */
    public function addBusiness(\AppBundle\Entity\Business $business)
    {
        $this->business[] = $business;

        return $this;
    }

    /**
     * Remove business
     *
     * @param \AppBundle\Entity\Business $business
     */
    public function removeBusiness(\AppBundle\Entity\Business $business)
    {
        $this->business->removeElement($business);
    }

    /**
     * Get business
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Add company
     *
     * @param \AppBundle\Entity\Company $company
     *
     * @return Task
     */
    public function addCompany(\AppBundle\Entity\Company $company)
    {
        $this->company[] = $company;

        return $this;
    }

    /**
     * Remove company
     *
     * @param \AppBundle\Entity\Company $company
     */
    public function removeCompany(\AppBundle\Entity\Company $company)
    {
        $this->company->removeElement($company);
    }

    /**
     * Get company
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add contact
     *
     * @param \AppBundle\Entity\Contact $contact
     *
     * @return Task
     */
    public function addContact(\AppBundle\Entity\Contact $contact)
    {
        $this->contact[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \AppBundle\Entity\Contact $contact
     */
    public function removeContact(\AppBundle\Entity\Contact $contact)
    {
        $this->contact->removeElement($contact);
    }

    /**
     * Get contact
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Add child
     *
     * @param \AppBundle\Entity\Task $child
     *
     * @return Task
     */
    public function addChild(\AppBundle\Entity\Task $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\Task $child
     */
    public function removeChild(\AppBundle\Entity\Task $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Task $parent
     *
     * @return Task
     */
    public function setParent(\AppBundle\Entity\Task $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Task
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set recurrence
     *
     * @param \AppBundle\Entity\Recurrence $recurrence
     *
     * @return Task
     */
    public function setRecurrence(\AppBundle\Entity\Recurrence $recurrence = null)
    {
        $this->recurrence = $recurrence;

        return $this;
    }

    /**
     * Get recurrence
     *
     * @return \AppBundle\Entity\Recurrence
     */
    public function getRecurrence()
    {
        return $this->recurrence;
    }

    public function unsetId()
    {
        $this->id = null;

    }

    /**
     * Set state
     *
     * @param \AppBundle\Entity\TaskState $state
     *
     * @return Task
     */
    public function setState(\AppBundle\Entity\TaskState $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\TaskState
     */
    public function getState()
    {
        return $this->state;
    }
}
