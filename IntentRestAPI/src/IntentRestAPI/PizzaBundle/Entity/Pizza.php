<?php

namespace IntentRestAPI\PizzaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pizza
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Pizza extends Food
{
    /**
     * @var string
     *
     * @ORM\Column(name="testField", type="string", length=255)
     */
    private $testField;

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
     * Set testField
     *
     * @param string $testField
     * @return Pizza
     */
    public function setTestField($testField)
    {
        $this->testField = $testField;

        return $this;
    }

    /**
     * Get testField
     *
     * @return string 
     */
    public function getTestField()
    {
        return $this->testField;
    }
}
