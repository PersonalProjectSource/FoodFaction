<?php

namespace IntentRestAPI\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * ProduitCommande
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\MainBundle\Entity\Repositories\ProduitCommandeRepository")
 */
class ProduitCommande
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
     * @ORM\ManyToOne(targetEntity="IntentRestAPI\MainBundle\Entity\Produit", inversedBy="commandes", cascade="persist")
     */
    private $produits;

    /**
     * @ORM\ManyToOne(targetEntity="IntentRestAPI\MainBundle\Entity\Commande", inversedBy="produits", cascade="persist")
     */
    private $commandes;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="device", type="string", length=255)
     */
    private $device = "Euros";

    /**
     * @var string
     *
     * @ORM\Column(name="taille", type="string", length=255)
     */
    private $taille;

    /**
     * @var string
     *
     * @ORM\Column(name="piment", type="string", length=255)
     */
    private $piment;

    /**
     * @var string
     *
     * @ORM\Column(name="sauce", type="string", length=255, nullable=true)
     */
    private $sauce;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaires", type="string", length=255)
     */
    private $commentaires;

    /**
     * @var int
     *
     * @ORM\Column(name="remise_ligne", type="integer", nullable=true)
     */
    private $remise;

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
     * Set quantite
     *
     * @param integer $quantite
     * @return ProduitCommande
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
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
     * Set commentaires
     *
     * @param string $commentaires
     * @return ProduitCommande
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * Get commentaires
     *
     * @return string 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Set produits
     *
     * @param \IntentRestAPI\MainBundle\Entity\Produit $produits
     * @return ProduitCommande
     */
    public function setProduits(\IntentRestAPI\MainBundle\Entity\Produit $produits = null)
    {
        $produits->addCommande($this);
        $this->produits = $produits;

        return $this;
    }

    /**
     * Get produits
     *
     * @return \IntentRestAPI\MainBundle\Entity\Produit 
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Set commandes
     *
     * @param \IntentRestAPI\MainBundle\Entity\Commande $commandes
     * @return ProduitCommande
     */
    public function setCommandes(\IntentRestAPI\MainBundle\Entity\Commande $commandes = null)
    {
        $commandes->addProduit($this);
        $this->commandes = $commandes;

        return $this;
    }

    /**
     * Get commandes
     *
     * @return \IntentRestAPI\MainBundle\Entity\Commande 
     */
    public function getCommandes()
    {
        return $this->commandes;
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
     * Set prix
     *
     * @param integer $prix
     * @return ProduitCommande
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
     * @return ProduitCommande
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
     * Set taille
     *
     * @param string $taille
     * @return ProduitCommande
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get taille
     *
     * @return string 
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set piment
     *
     * @param string $piment
     * @return ProduitCommande
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
     * Set sauce
     *
     * @param string $sauce
     * @return ProduitCommande
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
