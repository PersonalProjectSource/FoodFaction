<?php

namespace IntentRestAPI\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 * @ORM\Table(name="Facture")
 * @ORM\Entity(repositoryClass="IntentRestAPI\PizzaBundle\Entity\Repository\FactureRepository")
 */
class Facture extends Piece
{
    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Facture
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
}
