<?php

namespace IntentRestAPI\MainBundle\Services\Patterns\Factory;
use IntentRestAPI\MainBundle\Entity\Produit;
use IntentRestAPI\PizzaBundle\Entity\Pizza;


/**
 * Class ProduitFactory
 *
 * Classe gérant la creation des instances de Produit sous différentes formes et de différentes manière.
 */
class ProduitFactory {

    const PRODUIT = "Produit";
    const FOOD = "Food";
    const PIZZA = "Pizza";

    /**
     * @param String $sInstanceName
     * @return mixed
     */
    public function instanciateInheritedProduct($sInstanceName) {

        $sGobalInstance = '\IntentRestAPI\PizzaBundle\Entity\\'.$sInstanceName;
        return new $sGobalInstance();
    }

    /**
     * Transforme le flux Json recu en tableau d'instance d'objets.
     *
     * @param $aDatasJsonToTransform
     * @param $sInstanceName
     * @return array
     */
    public function createAllInheritedProduct($aDatasJsonToTransform, $sInstanceName) {

        // TODO rendre l'instanciation des produits dynamique avec toute les classes hérités.
        $aoAllProduits = array();
        foreach ($aDatasJsonToTransform as $mKey => $sValue) {

            $oCurrentProduit = new Pizza();
            $oCurrentProduit->setNom($sValue['nom']);
            $oCurrentProduit->setPrix($sValue['prix']);
            $oCurrentProduit->setDevice($sValue['device']);
            $oCurrentProduit->setType($sValue['type']);
            $oCurrentProduit->setPiment($sValue['piment']);
            $oCurrentProduit->setActif($sValue['actif']);
            $oCurrentProduit->setTaille($sValue['taille']);
            $oCurrentProduit->setDescription("");
            $aoAllProduits[] = $oCurrentProduit;
        }

        return $aoAllProduits;
    }
}