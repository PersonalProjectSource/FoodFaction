<?php

namespace IntentRestAPI\UserBundle\Services;

use IntentRestAPI\MainBundle\Entity\User;
use IntentRestAPI\UserBundle\Entity\Notification;

class NotificationService {


    /**
     *
     *
     * @param User $oDestinataire
     * @return Notification
     */
    public function SendNotification(User $oDestinataire) {

        $oNotification = new Notification();
        $oNotification->setTypeNotif('Notification');
        $oNotification->setDate('now');
        $oNotification->setContenu('contenu de la notification');
        $oNotification->setPicture('path de l\'image');
        $oNotification->setUser($oDestinataire);

        return $oNotification;
    }
}