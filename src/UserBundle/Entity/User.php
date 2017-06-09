<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * UserBundle\Entity\User
 *
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     */
    protected $groups;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Type("integer")
     */
    protected $id;

    /**
     * @Serializer\Type("string")
     */
    protected $username;

    /**
     * @Serializer\Type("string")
     */
    protected $email;

    /**
     * @ORM\Column(nullable=true)
     * @Serializer\Type("string")
     */
    protected $firstName;

    /**
     * @ORM\Column(nullable=true)
     * @Serializer\Type("string")
     */
    protected $lastName;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Serializer\Type("DateTime")
     */
    protected $dateOfBirth;

    /**
     * @ORM\Column(length=8, nullable=true)
     * @Serializer\Type("string")
     */
    protected $gender;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Serializer\Type("integer")
     */
    protected $level = 50;

    /**
     * @ORM\Column(type="boolean")
     * @Serializer\Type("boolean")
     */
    protected $profileVisibleToThePublic = false;

    /**
     * @ORM\Column(nullable=true)
     * @Serializer\Type("string")
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Type("string")
     */
    protected $info;

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return User
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set profileVisibleToThePublic
     *
     * @param boolean $profileVisibleToThePublic
     *
     * @return User
     */
    public function setProfileVisibleToThePublic($profileVisibleToThePublic)
    {
        $this->profileVisibleToThePublic = $profileVisibleToThePublic;

        return $this;
    }

    /**
     * Get profileVisibleToThePublic
     *
     * @return boolean
     */
    public function getProfileVisibleToThePublic()
    {
        return $this->profileVisibleToThePublic;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return User
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
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
}
