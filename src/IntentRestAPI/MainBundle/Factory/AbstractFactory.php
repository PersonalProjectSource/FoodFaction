<?php

namespace IntentRestAPI\MainBundle\Factory;



abstract class  AbstractFactory {

    const NAMESPACE_MAIN = '\IntentRestAPI\MainBundle\Entity\\';

    /**
     * Renvoi une instance faite dynamiquement en fonction des parametres
     *
     * @param $sNamespace
     * @param $sType
     * @return Object
     */
    public static function getInstance($sNamespace, $sType) {

        $sInstanceName = $sNamespace.$sType;
        return new $sInstanceName();
    }
}