<?php

namespace IntentRestAPI\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Panier
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\UserBundle\Entity\Repositories\PanierRepository")
 */
class Panier implements EngineObject
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
     * @ORM\OneToOne("IntentRestAPI\UserBundle\Entity\MainUser")
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;

    /**
     * @var integer
     *
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;


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
     * Set nom
     *
     * @param string $nom
     * @return Panier
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Panier
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     * @return Panier
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set owner
     *
     * @param \IntentRestAPI\UserBundle\Entity\MainUser $owner
     * @return Panier
     */
    public function setOwner(\IntentRestAPI\UserBundle\Entity\MainUser $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \IntentRestAPI\UserBundle\Entity\MainUser 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Transforme un objet en array
     * Methode devant obligatoirement etre surchargée par les classes filles.
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
