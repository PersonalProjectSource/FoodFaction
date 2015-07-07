<?php

namespace IntentRestAPI\MainBundle\Factory;


class ProduitCommandeFactory extends AbstractFactory {

    const NAMESPACE_PRODUIT_COMMANDE = '\IntentRestAPI\MainBundle\Entity\\';

    /**
     * Renvoi la bonne instance de ProduitCommande.
     *
     * @param $sType
     * @return Object
     */
    public static function getInstanceProduit($sType) {

        $sNamespacePiece = self::NAMESPACE_FOOD_METIER;
        if ("ProduitCommande" == ucfirst($sType)) {
            $sNamespacePiece = self::NAMESPACE_MAIN;
        }

        return parent::getInstance($sNamespacePiece, $sType);
    }
}