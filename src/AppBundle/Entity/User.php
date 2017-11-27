<?php
/**
 * Created by PhpStorm.
 * User: paulinho
 * Date: 30.10.2017
 * Time: 23:38
 */

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Contributor", mappedBy="user")
     *
     * @var \AppBundle\Entity\Contributor
     */
    private $contributor;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Ward", mappedBy="user")
     *
     * @var \AppBundle\Entity\Ward
     */
    private $ward;

    /**
     * @ORM\Column(name="type", type="string", columnDefinition="enum('contributor', 'ward', 'admin')")
     *
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}