<?php

namespace IntentRestAPI\PizzaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Salade
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\PizzaBundle\Entity\Repositories\SaladeRepository")
 */
class Salade extends Food
{



    /**
     * Set sauce
     *
     * @param string $sauce
     * @return Salade
     */
    public function setSauce($sauce)
    {
        $this->sauce = $sauce;

        return $this;
    }

    /**
     * Get sauce
     *
     * @return string 
     */
    public function getSauce()
    {
        return $this->sauce;
    }
}
