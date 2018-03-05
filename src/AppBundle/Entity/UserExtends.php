<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * UserExtends
 *
 * @ORM\Table(name="user_extends")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserExtendsRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class UserExtends
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
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\User", mappedBy="userExtends")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Serializer\Expose()
     * @Serializer\SerializedName("user")
     */
    private $idUser;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Study")
     * @ORM\JoinColumn(name="study_id", referencedColumnName="id")
     * @Serializer\Expose()
     * @Serializer\SerializedName("study")
     */
    private $idStudy;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Team", mappedBy="userExtendsCollection")
     */
    private $teamsCollection;





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
     * Set idUser
     *
     * @param \ApiBundle\Entity\User $idUser
     *
     * @return UserExtends
     */
    public function setIdUser(\ApiBundle\Entity\User $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \ApiBundle\Entity\User
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idStudy
     *
     * @param \AppBundle\Entity\Study $idStudy
     *
     * @return UserExtends
     */
    public function setIdStudy(\AppBundle\Entity\Study $idStudy = null)
    {
        $this->idStudy = $idStudy;

        return $this;
    }

    /**
     * Get idStudy
     *
     * @return \AppBundle\Entity\Study
     */
    public function getIdStudy()
    {
        return $this->idStudy;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teamsCollection = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add teamsCollection
     *
     * @param \AppBundle\Entity\Team $teamsCollection
     *
     * @return UserExtends
     */
    public function addTeamsCollection(\AppBundle\Entity\Team $teamsCollection)
    {
        $this->teamsCollection[] = $teamsCollection;

        return $this;
    }

    /**
     * Remove teamsCollection
     *
     * @param \AppBundle\Entity\Team $teamsCollection
     */
    public function removeTeamsCollection(\AppBundle\Entity\Team $teamsCollection)
    {
        $this->teamsCollection->removeElement($teamsCollection);
    }

    /**
     * Get teamsCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeamsCollection()
    {
        return $this->teamsCollection;
    }
}
