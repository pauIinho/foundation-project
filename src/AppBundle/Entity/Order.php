<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ward", inversedBy="orders")
     * @ORM\JoinColumn(name="ward_id", referencedColumnName="id")
     *
     * @var \AppBundle\Entity\Ward
     */
    private $ward;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Donation", mappedBy="orders")
     *
     * @var \AppBundle\Entity\Donation
     */
    private $donations;


    /**
     * @ORM\Column(name="start_date", type="datetime")
     *
     * @var \DateTime
     */
    private $startDate;

    /**
     * @ORM\Column(name="close_date", type="datetime")
     *
     * @var \DateTime
     */
    private $closeDate;

    /**
     * @ORM\Column(name="status", type="integer")
     *
     * @var integer
     */
    private $status;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->donations = new ArrayCollection();
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
     * @return Ward
     */
    public function getWard()
    {
        return $this->ward;
    }

    /**
     * @param Ward $ward
     */
    public function setWard($ward)
    {
        $this->ward = $ward;
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
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getCloseDate()
    {
        return $this->closeDate;
    }

    /**
     * @param \DateTime $closeDate
     */
    public function setCloseDate($closeDate)
    {
        $this->closeDate = $closeDate;
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
}