<?php

namespace IntentRestAPI\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;

# import metiers
use IntentRestAPI\MainBundle\Entity\Produit;
use IntentRestAPI\PizzaBundle\Entity\Food;
use IntentRestAPI\MainBundle\Strategy\ClassTools;
use IntentRestAPI\MainBundle\Strategy\MainClassTools;
use IntentRestAPI\UserBundle\Entity\EngineObject;
use IntentRestAPI\MainBundle\Entity\ProduitCommande;

/**
 * Commande
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\MainBundle\Entity\Repository\CommandeRepository")
 */
class Commande implements EngineObject
{
    const API_DATE_FORMAT = "j.n.Y H:m:s";

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="ProduitCommande", mappedBy="commandes", cascade="persist")
     */
    private $produits;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="commandes", cascade={"persist"})
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="remarque", type="string", length=255)
     */
    private $remarque;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_commande", type="integer")
     */
    private $prixCommande;

    /**
     * @var Integer
     *
     * @ORM\Column(name="remise_commande", type="integer")
     */
    private $remise;

    /**
     * @var String
     *
     * @ORM\Column(name="etat_commande", type="string", length=150)
     */
    private $etat;

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
     * @return Commande
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
     * @return Commande
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
     *
     */
    public function __toString()
    {
        return $this->nom;
    }

    /**
     * Set remarque
     *
     * @param string $remarque
     * @return Commande
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string 
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * Set date
     *
     * @param string $date
     * @return ProduitCommande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set Prix
     *
     * @param string $date
     * @return ProduitCommande
     */
    public function setPrix($prixCommande)
    {
        $this->prixCommande = $prixCommande;

        return $this;
    }

    /**
     * Get Prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prixCommande;
    }

    /**
     * Set Remise
     *
     * @param Integer $remise
     * @return Integer
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get Remise
     *
     * @return Integer
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * Set etat commande
     *
     * @param string $remarque
     * @return Commande
     */
    public function setEtat($sNextStat)
    {
        $this->etat = $sNextStat;

        return $this;
    }

    /**
     * Get etat de la commande
     *
     * @return string
     */
    public function getEtatCommande()
    {
        return $this->etat;
    }

    /**
     *
     *
     * @return array
     */
    public function spawnInArray() {

        $aCommande = array();

        foreach ($this as $sAttribName => $sAttribValue) {
            if (is_object($sAttribValue) && $sAttribValue instanceof PersistentCollection) {
                foreach ($sAttribValue as $oObject) {
                    $aCommande['produits'][] = $oObject->spawnInArray();
                }
            }
            elseif (!is_object($sAttribValue)) {
                $aCommande[$sAttribName] = $sAttribValue;
            }
        }
        return $aCommande;
    }

    /**
     * Test d'iteration pour tout les objets hérités.
     */
   /* public function iterate($oObject = null) {
        echo "Présentation de l'iteration cmd : <br>";
        $oObject = (null == $oObject) ? $this : $oObject;
        foreach ($oObject as $key => $value) {
            echo "boucle<br>";
            if ($value instanceof Commande) {
                echo "recursion<br>";
                $value->iterate($value);
            }
            echo "key : ".$key." => ".$value."<br>";
            $aProduit[$key] = $value;
        }
    }*/

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etat = "en_traitement";
    }

    /**
     * Add produits
     *
     * @param \IntentRestAPI\MainBundle\Entity\ProduitCommande $produits
     * @return Commande
     */
    public function addProduit(\IntentRestAPI\MainBundle\Entity\ProduitCommande $produits)
    {
        $this->produits[] = $produits;

        return $this;
    }

    /**
     * Remove produits
     *
     * @param \IntentRestAPI\MainBundle\Entity\ProduitCommande $produits
     */
    public function removeProduit(\IntentRestAPI\MainBundle\Entity\ProduitCommande $produits)
    {
        $this->produits->removeElement($produits);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Set prixCommande
     *
     * @param integer $prixCommande
     * @return Commande
     */
    public function setPrixCommande($prixCommande)
    {
        $this->prixCommande = $prixCommande;

        return $this;
    }

    /**
     * Get prixCommande
     *
     * @return integer 
     */
    public function getPrixCommande()
    {
        return $this->prixCommande;
    }

    /**
     * Get etat
     *
     * @return string 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set user
     *
     * @param \IntentRestAPI\MainBundle\Entity\User $user
     * @return Commande
     */
    public function setUser(\IntentRestAPI\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \IntentRestAPI\MainBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
