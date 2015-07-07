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
    const SAUCE_DEFAULT_CHOICE = false;


    /**
     * Set taille
     *
     * @param string $taille
     * @return Food
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get piment
     *
     * @return string
     */
    public function getTaille()
    {
        return $this->piment;
    }

    /**
     * Set piment
     *
     * @param string piment
     * @return Food
     */
    public function setPiment($piment)
    {
        $this->piment = $piment;

        return $this;
    }

    /**
     * Get piment
     *
     * @return string
     */
    public function getPiment()
    {
        return $this->piment;
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
