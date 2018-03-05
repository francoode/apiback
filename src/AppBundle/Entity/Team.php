<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Team
 *
 * @ORM\Table(name="team")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeamRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Team
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\UserExtends", inversedBy="teamsCollection")
     * @ORM\JoinTable(name="user_extends_teams")
     *
     */
    private $userExtendsCollection;


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
     * @return Team
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
     * Constructor
     */
    public function __construct()
    {
        $this->userExtendsCollection = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userExtendsCollection
     *
     * @param \AppBundle\Entity\UserExtends $userExtendsCollection
     *
     * @return Team
     */
    public function addUserExtendsCollection(\AppBundle\Entity\UserExtends $userExtendsCollection)
    {
        $this->userExtendsCollection[] = $userExtendsCollection;

        return $this;
    }

    /**
     * Remove userExtendsCollection
     *
     * @param \AppBundle\Entity\UserExtends $userExtendsCollection
     */
    public function removeUserExtendsCollection(\AppBundle\Entity\UserExtends $userExtendsCollection)
    {
        $this->userExtendsCollection->removeElement($userExtendsCollection);
    }

    /**
     * Get userExtendsCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserExtendsCollection()
    {
        return $this->userExtendsCollection;
    }
}
