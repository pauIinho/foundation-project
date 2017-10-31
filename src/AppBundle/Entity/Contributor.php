<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contributors")
 */
class Contributor
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="contributor")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Donation", mappedBy="contributor")
     *
     * @var \AppBundle\Entity\Donation
     */
    private $donations;

    /**
     * @ORM\Column(name="name", type="string", length=100)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="organization_name", type="string", length=100)
     *
     * @var string
     */
    private $organizationName;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Donation
     */
    public function getDonations()
    {
        return $this->donations;
    }

    /**
     * @param Donation $donations
     */
    public function setDonations($donations)
    {
        $this->donations = $donations;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getOrganizationName()
    {
        return $this->organizationName;
    }

    /**
     * @param string $organizationName
     */
    public function setOrganizationName($organizationName)
    {
        $this->organizationName = $organizationName;
    }
}