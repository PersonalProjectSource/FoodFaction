<?php

namespace IntentRestAPI\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use IntentRestAPI\UserBundle\Entity\EngineObject;
use Symfony\Component\Console\Command\Command;

/**
 * Piece
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type_produit", type="string")
 * @ORM\DiscriminatorMap
 * ({
 * "foood" = "IntentRestAPI\PizzaBundle\Entity\Food",
 * "pizza" = "IntentRestAPI\PizzaBundle\Entity\Pizza",
 * "salade"= "IntentRestAPI\PizzaBundle\Entity\Salade",
 * "drink" = "IntentRestAPI\PizzaBundle\Entity\Drink",
 * })
 * @ORM\Entity(repositoryClass="IntentRestAPI\MainBundle\Entity\Repository\ProduitRepository")
 */
class Produit implements EngineObject
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="IntentRestAPI\MainBundle\Entity\ProduitCommande", mappedBy="produits")
     */
    private $commandes;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
     */
    protected $nom;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    protected $actif;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="url_image", type="string", length=255)
     */
    protected $urlImage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Produit
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
     * @return Produit
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
     * Set description
     *
     * @param string $description
     * @return Produit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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

    /**
     * Test d'iteration pour tout les objets hérités.
     */
    public function iterate($oObject = null) {
        $oObject = (null == $oObject) ? $this : $oObject;
        $aProduit = array();

        foreach ($oObject as $key => $value) {
            if (is_object($value)) {
                foreach ($value as $va) {
                    echo "c";
                    var_dump($va);
                }
                $this->iterate($value);
            }
            else {
                echo'b';
                $aProduit[$key] = $value;
            }
        }
        var_dump("<PRE>",$aProduit);
    }


    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommandes()
    {
        return $this->commandes;
    }


    /**
     * Add commandes
     *
     * @param \IntentRestAPI\MainBundle\Entity\ProduitCommande $commandes
     * @return Produit
     */
    public function addCommande(\IntentRestAPI\MainBundle\Entity\ProduitCommande $commandes)
    {
        $this->commandes[] = $commandes;

        return $this;
    }

    /**
     * Remove commandes
     *
     * @param \IntentRestAPI\MainBundle\Entity\ProduitCommande $commandes
     */
    public function removeCommande(\IntentRestAPI\MainBundle\Entity\ProduitCommande $commandes)
    {
        $this->commandes->removeElement($commandes);
    }

    /**
     * Set actif
     *
     * @param String $url
     * @return String $url
     */
    public function setUrlImage($url)
    {
        $this->urlImage = $url;
        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean
     */
    public function getUrlImage()
    {
        return $this->urlImage;
    }
}
