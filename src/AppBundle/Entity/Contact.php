<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Contact
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
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\SerializedName("lastname")
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\SerializedName("email")
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="isClient", type="boolean")
     * @Serializer\Expose()
     * @Serializer\SerializedName("isClient")
     */
    private $isClient;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userOwner;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Company", inversedBy="contactCollection")
     * @ORM\JoinTable(name="contacts_companies")
     *
     */
    private $companyCollection;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Study")
     * @ORM\JoinColumn(name="study_id", referencedColumnName="id")
     */
    private $study;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Business", mappedBy="contactCollection")
     */
    private $businessCollection;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\LogAction", mappedBy="contact")
     */
    private $logCollection;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Note", mappedBy="contact")
     */
    private $noteCollection;



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
     * @return Contact
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
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Contact
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isClient
     *
     * @param boolean $isClient
     *
     * @return Contact
     */
    public function setIsClient($isClient)
    {
        $this->isClient = $isClient;

        return $this;
    }

    /**
     * Get isClient
     *
     * @return bool
     */
    public function getIsClient()
    {
        return $this->isClient;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->companyCollection = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add companyCollection
     *
     * @param \AppBundle\Entity\Company $companyCollection
     *
     * @return Contact
     */
    public function addCompanyCollection(\AppBundle\Entity\Company $companyCollection)
    {
        $this->companyCollection[] = $companyCollection;

        return $this;
    }

    /**
     * Remove companyCollection
     *
     * @param \AppBundle\Entity\Company $companyCollection
     */
    public function removeCompanyCollection(\AppBundle\Entity\Company $companyCollection)
    {
        $this->companyCollection->removeElement($companyCollection);
    }

    /**
     * Get companyCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanyCollection()
    {
        return $this->companyCollection;
    }

    /**
     * Set userOwner
     *
     * @param \ApiBundle\Entity\User $userOwner
     *
     * @return Contact
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
     * @return Contact
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
     * Add businessCollection
     *
     * @param \AppBundle\Entity\Business $businessCollection
     *
     * @return Contact
     */
    public function addBusinessCollection(\AppBundle\Entity\Business $businessCollection)
    {
        $this->businessCollection[] = $businessCollection;

        return $this;
    }

    /**
     * Remove businessCollection
     *
     * @param \AppBundle\Entity\Business $businessCollection
     */
    public function removeBusinessCollection(\AppBundle\Entity\Business $businessCollection)
    {
        $this->businessCollection->removeElement($businessCollection);
    }

    /**
     * Get businessCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBusinessCollection()
    {
        return $this->businessCollection;
    }

    /**
     * Add logCollection
     *
     * @param \AppBundle\Entity\LogAction $logCollection
     *
     * @return Contact
     */
    public function addLogCollection(\AppBundle\Entity\LogAction $logCollection)
    {
        $this->logCollection[] = $logCollection;

        return $this;
    }

    /**
     * Remove logCollection
     *
     * @param \AppBundle\Entity\LogAction $logCollection
     */
    public function removeLogCollection(\AppBundle\Entity\LogAction $logCollection)
    {
        $this->logCollection->removeElement($logCollection);
    }

    /**
     * Get logCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogCollection()
    {
        return $this->logCollection;
    }

    /**
     * Add noteCollection
     *
     * @param \AppBundle\Entity\Note $noteCollection
     *
     * @return Contact
     */
    public function addNoteCollection(\AppBundle\Entity\Note $noteCollection)
    {
        $this->noteCollection[] = $noteCollection;

        return $this;
    }

    /**
     * Remove noteCollection
     *
     * @param \AppBundle\Entity\Note $noteCollection
     */
    public function removeNoteCollection(\AppBundle\Entity\Note $noteCollection)
    {
        $this->noteCollection->removeElement($noteCollection);
    }

    /**
     * Get noteCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNoteCollection()
    {
        return $this->noteCollection;
    }
}
