<?php
/**
 * Created by PhpStorm.
 * User: laurentbrau
 * Date: 23/05/15
 * Time: 00:48
 */

namespace IntentRestAPI\UserBundle\Entity;


interface EngineObject {

    /**
     * Transforme un objet en array
     * @return mixed
     */
    public function spawnInArray();
}