<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Note
/**
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoteRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Note
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
     * @ORM\Column(name="description", type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\SerializedName("description")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Serializer\Expose()
     * @Serializer\SerializedName("userOwner")
     */
    protected $userOwner;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Study")
     * @ORM\JoinColumn(name="study_id", referencedColumnName="id")
     */
    protected $study;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Business", inversedBy="noteCollection")
     * @ORM\JoinTable(name="note_business")
     * @Serializer\Expose()
     * @Serializer\SerializedName("business")
     */
    private $business;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Company", inversedBy="noteCollection")
     * @ORM\JoinTable(name="note_company")
     * @Serializer\Expose()
     * @Serializer\SerializedName("company")
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Contact", inversedBy="noteCollection")
     * @ORM\JoinTable(name="note_contact")
     * @Serializer\Expose()
     * @Serializer\SerializedName("contact")
     */
    private $contact;
    

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
     * Add business
     *
     * @param \AppBundle\Entity\Business $business
     *
     * @return Note
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
     * @return Note
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
     * @return Note
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Note
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
     * Set userOwner
     *
     * @param \ApiBundle\Entity\User $userOwner
     *
     * @return Note
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
     * @return Note
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
