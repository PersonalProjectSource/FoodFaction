<?php

namespace IntentRestAPI\MainBundle\Services;

/**
 * Interface ClassTools
 * Implémente les methodes de traitements dont les classes metiers peuvent avoir besoin pour la gestion des données.
 */
class  ClassTools {

    static function jsonToArray($sJsonStream) {
        var_dump(json_decode($sJsonStream, true), 'mode debug jsonToArray');
        die;
    }

    static function arrayToJson($aArrayDatas) {
        var_dump(json_encode($aArrayDatas), 'mode debug arrayToJson');
        die;
    }
}