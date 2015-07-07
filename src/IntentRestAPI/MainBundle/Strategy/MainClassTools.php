<?php

namespace IntentRestAPI\MainBundle\Strategy;

use IntentRestAPI\MainBundle\Strategy\ClassTools;

class MainClassTools implements ClassTools{


    /**
     * @Override
     * Test d'iteration pour tout les objets hérités.
     */
    public function iterate($oObject = null) {
        $oObject = (null == $oObject) ? $this : $oObject;

        $aProduit = array();
        foreach ($oObject as $key => $value) {
            if (is_object($value)) {
                foreach ($value as $va) {
                    var_dump($va);
                }

                $this->iterate($value);
            }
            else {
                $aProduit[$key] = $value;
            }
        }
        var_dump("<PRE>",$aProduit);
    }
}