<?php
/**
 * Created by PhpStorm.
 * User: laurentbrau
 * Date: 29/05/15
 * Time: 12:22
 */

namespace IntentRestAPI\MainBundle\Factory;

use IntentRestAPI\MainBundle\Factory\AbstractFactory;


class MainFactory extends AbstractFactory {

    const NAMESPACE_FOOD_METIER = '\IntentRestAPI\PizzaBundle\Entity\\';

    public static function getInstancePiece($sType) {

        $sNamespacePiece = self::NAMESPACE_FOOD_METIER;
        if ("Piece" == ucfirst($sType)) {
            $sNamespacePiece = self::NAMESPACE_MAIN;
        }

        return parent::getInstance($sNamespacePiece, $sType);
    }

    public static function getInstanceProduit($sType) {

        $sNamespacePiece = self::NAMESPACE_FOOD_METIER;
        if ("Produit" == ucfirst($sType)) {
            $sNamespacePiece = self::NAMESPACE_MAIN;
        }

        return parent::getInstance($sNamespacePiece, $sType);
    }
}