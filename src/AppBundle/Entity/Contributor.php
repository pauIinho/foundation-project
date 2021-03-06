<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContributorRepository")
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="organization_name", type="string", length=100, nullable=true)
     *
     * @var string
     */
    private $organizationName;

    /**
     * @ORM\Column(name="contact_phone", type="string", length=100, nullable=true)
     *
     * @var string
     */
    private $contactPhone;

    /**
     * @ORM\Column(name="description", type="string", nullable=true)
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(name="image", nullable=true, type="text")
     *
     * @Assert\NotBlank(message="Пожалуйста, загрузите файл")
     * @Assert\File(
     *     maxSize="1024k",
     *     mimeTypes={"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Загрузите PNG или JPEG файл"
     * )
     * @var File
     */
    private $image;

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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param string $contactPhone
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}