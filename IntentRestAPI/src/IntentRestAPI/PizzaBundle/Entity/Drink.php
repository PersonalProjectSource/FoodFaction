<?php

namespace IntentRestAPI\PizzaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IntentRestAPI\MainBundle\Entity\Produit;

/**
 * Drink
 *
 * @ORM\Entity(repositoryClass="IntentRestAPI\PizzaBundle\Entity\DrinkRepository")
 */
class Drink extends Produit
{

    /**
     * @var integer
     *
     * @ORM\Column(name="format", type="integer")
     */
    private $format;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=255)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * Set format
     *
     * @param integer $format
     * @return Drink
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return integer 
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set marque
     *
     * @param string $marque
     * @return Drink
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return string 
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Drink
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
