<?php

namespace IntentRestAPI\PizzaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IntentRestAPI\MainBundle\Entity\Produit;

/**
 * Food
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\PizzaBundle\Entity\Repositories\FoodRepository")
 */
class Food extends Produit
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;




    /**
     * Constructeur
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Food
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

    /**
     * Set prix
     *
     * @param integer $prix
     * @return Food
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set device
     *
     * @param string $device
     * @return Food
     */
    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return string 
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Transforme un objet en array
     */
    public function spawnInArray() {
        foreach ($this as $sAttribName => $mAttribValue) {
            if (!is_object($mAttribValue)) {
                $aProduit[$sAttribName] = $mAttribValue;
            }
        }
        return $aProduit;
    }

}
