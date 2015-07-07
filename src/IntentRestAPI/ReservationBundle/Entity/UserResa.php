<?php

namespace IntentRestAPI\ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IntentRestAPI\UserBundle\Entity\MainUser;

/**
 * UserResa
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\ReservationBundle\Entity\Repositories\UserResaRepository")
 */
class UserResa extends MainUser
{
    /**
     * @var string
     *
     * @ORM\Column(name="userResaFieldTest", type="string", length=255)
     */
    private $userResaFieldTest;

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
     * Set userResaFieldTest
     *
     * @param string $userResaFieldTest
     * @return UserResa
     */
    public function setUserResaFieldTest($userResaFieldTest)
    {
        $this->userResaFieldTest = $userResaFieldTest;

        return $this;
    }

    /**
     * Get userResaFieldTest
     *
     * @return string 
     */
    public function getUserResaFieldTest()
    {
        return $this->userResaFieldTest;
    }
}
