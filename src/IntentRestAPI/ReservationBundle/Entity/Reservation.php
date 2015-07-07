<?php

namespace IntentRestAPI\ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\ReservationBundle\Entity\Repositories\ReservationRepository")
 */
class Reservation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="fieldTest", type="string", length=255)
     */
    private $fieldTest;


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
     * Set fieldTest
     *
     * @param string $fieldTest
     * @return Reservation
     */
    public function setFieldTest($fieldTest)
    {
        $this->fieldTest = $fieldTest;

        return $this;
    }

    /**
     * Get fieldTest
     *
     * @return string 
     */
    public function getFieldTest()
    {
        return $this->fieldTest;
    }
}
