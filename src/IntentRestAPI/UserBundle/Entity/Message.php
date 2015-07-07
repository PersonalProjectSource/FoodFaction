<?php

namespace IntentRestAPI\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\UserBundle\Entity\Repositories\MessageRepository")
 */
class Message implements EngineObject
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
     * @ORM\ManyToOne(targetEntity="IntentRestAPI\UserBundle\Entity\MainUser")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=150, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;

    public function __construct() {
        $this->actif = true;
    }

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
     * Set commandes
     *
     * @param \IntentRestAPI\MainBundle\Entity\Commande $commandes
     * @return ProduitCommande
     */
    public function setUser(\IntentRestAPI\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get commandes
     *
     * @return \IntentRestAPI\MainBundle\Entity\Commande
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Message
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Message
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Message
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
