<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DonationRepository")
 * @ORM\Table(name="donations")
 */
class Donation
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contributor", inversedBy="donations")
     * @ORM\JoinColumn(name="contributor_id", referencedColumnName="id")
     *
     * @var \AppBundle\Entity\Contributor
     */
    private $contributor;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Order", inversedBy="donations")
     * @ORM\JoinTable(name="orders_donations")
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
     * @ORM\Column(name="description", type="string")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(name="count", type="integer")
     *
     * @var string
     */
    private $count;

    /**
     * @ORM\Column(name="receipt_date", type="datetime")
     *
     * @var \DateTime
     */
    private $receiptDate;

    /**
     * @ORM\Column(name="status", type="integer")
     *
     * @var integer
     */
    private $status;

    /**
     * @ORM\Column(name="image_path", type="string")
     *
     * @var string
     */
    private $imagePath;

    /**
     * Donation constructor.
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

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
     * @return Contributor
     */
    public function getContributor()
    {
        return $this->contributor;
    }

    /**
     * @param Contributor $contributor
     */
    public function setContributor($contributor)
    {
        $this->contributor = $contributor;
    }

    /**
     * @return string
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param string $count
     */
    public function setCount($count)
    {
        $this->count = $count;
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
     * @return \DateTime
     */
    public function getReceiptDate()
    {
        return $this->receiptDate;
    }

    /**
     * @param \DateTime $receiptDate
     */
    public function setReceiptDate($receiptDate)
    {
        $this->receiptDate = $receiptDate;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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