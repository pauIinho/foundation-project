<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WardRepository")
 * @ORM\Table(name="wards")
 */
class Ward
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Order", mappedBy="ward")
     *
     * @var \AppBundle\Entity\Order
     */
    private $orders;

    /**
     * @ORM\Column(name="name", type="string", length=100)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="address", type="string", length=100)
     *
     * @var string
     */
    private $address;

    /**
     * @ORM\Column(name="description", type="string")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(name="image_path", type="string")
     *
     * @var string
     */
    private $imagePath;

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
     * @return Order
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param Order $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
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
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    }
}