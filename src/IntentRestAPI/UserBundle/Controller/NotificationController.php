<?php

namespace IntentRestAPI\UserBundle\Controller;

use IntentRestAPI\MainBundle\Entity\Commande;
use Proxies\__CG__\IntentRestAPI\MainBundle\Entity\ProduitCommande;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IntentRestAPI\UserBundle\Entity\Notification;
use IntentRestAPI\UserBundle\Form\NotificationType;

/**
 * Notification controller.
 *
 * @Route("/notification")
 */
class NotificationController extends Controller
{

    const SUCCESS_MESSAGE_NOTIFICAION_CREATED = "La notification a ete creee avec succes";
    const SUCCESSS_DELETE_NOTIFICATION = "La notification a ete supprimee avec succes";

    /**
     * Lists all Notification entities.
     *
     * @Route("/", name="notification")
     * @Method("GET")
     */
    public function indexAction()
    {
        $oEm = $this->getDoctrine()->getManager();
        $aoNotifications = $oEm->getRepository('IntentRestAPIUserBundle:Notification')->findAll();

        foreach ($aoNotifications as $oNotification) {
            $aNotifications[] = $oNotification->spawnInArray();
        }

        $oResponse = new JsonResponse();
        $oResponse->setData($aNotifications);

        return $oResponse;
    }

    /**
     * Displays a form to create a new Notification entity.
     *
     * {
        "picture": "path de l'image sur le serveur",
        "contenu": "contenu de la notification via le flux",
        "date": "20-05-17",
        "type": "Notification"
        }
     *
     * @Route("/new", name="notification_new")
     * @Method("POST")
     */
    public function newAction(Request $oRequest)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oNotification = new Notification();
        $aNotifications = json_decode($oRequest->getContent(), true);

        // TODO faire une methode d'hydratation dynamique d'un objet via un flux json.
        $oNotification
            ->setPicture($aNotifications['picture'])
            ->setContenu($aNotifications['contenu'])
            ->setDate($aNotifications['date'])
            ->setTypeNotif('Reclamation')
        ;

        $oEm->persist($oNotification);
        $oEm->flush();

        $aNotificationResponse = array(
            'id' => $oNotification->getId(),
            'nom' => $oNotification->getTypeNotif(),
            'message' => self::SUCCESS_MESSAGE_NOTIFICAION_CREATED
        );

        $oResponse = new JsonResponse();
        $oResponse->setData($aNotificationResponse);

        return $oResponse;
    }

    /**
     * Finds and displays a Notification entity.
     *
     * @Route("/{iId}", name="notification_show")
     * @Method("GET")
     */
    public function showAction($iId)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oNotification = $oEm->getRepository('IntentRestAPIUserBundle:Notification')->find($iId);

        if (!$oNotification) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $aNotificationResponse = array(
            "id" => $oNotification->getId(),
            "typenotif" => $oNotification->getTypeNotif(),
            "date" => $oNotification->getDate()
        );

        $oResponse = new JsonResponse();
        $oResponse->setData($aNotificationResponse);

        return $oResponse;
    }

    /**
     * Edits an existing Notification entity.
     *
     * @Route("/{id}", name="notification_update")
     * @Method("PUT")
     * @Template("IntentRestAPIUserBundle:Notification:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntentRestAPIUserBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('notification_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Notification entity.
     *
     * @Route("/{iId}", name="notification_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $oRequest, $iId)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oNotification = $oEm->getRepository('IntentRestAPIUserBundle:Notification')->find($iId);
        $aRequestContent = json_decode($oRequest->getContent(), true);

        if (!$oNotification) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $aDataMessage = array(
            "id" => $oNotification->getId(),
            "message" => self::SUCCESSS_DELETE_NOTIFICATION
        );

        $oEm->remove($oNotification);
        $oEm->flush();

        $oResponse = new JsonResponse();
        $oResponse->setData($aDataMessage);


        return $oResponse;
    }
}
