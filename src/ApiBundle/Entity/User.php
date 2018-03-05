<?php

namespace ApiBundle\Entity;

use ApiBundle\Traits\Blameable;
use ApiBundle\Traits\Timestampable;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table("user")
 * @ORM\Entity(repositoryClass="ApiBundle\Entity\Repository\UsersRepository")
 * @UniqueEntity(fields="email", errorPath="email", message="El correo ya está en uso")
 * @Serializer\ExclusionPolicy("NONE")
 */
class User extends BaseUser
{
    use Timestampable;
    use Blameable;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"basic_user"})
     */
    protected $id;

    /**
     * @var ApiKey
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\ApiKey", inversedBy="user")
     * @Serializer\Exclude()
     */
    private $apiKey;

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
     * @return \ApiBundle\Entity\ApiKey
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param \ApiBundle\Entity\ApiKey $apiKey
     *
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }


}
