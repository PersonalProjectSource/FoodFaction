<?php

namespace IntentRestAPI\MainBundle\Controller;

use IntentRestAPI\MainBundle\Factory\MainFactory;
use IntentRestAPI\MainBundle\Services\Patterns\Factory\ProduitFactory;
use IntentRestAPI\PizzaBundle\Entity\Food;
use IntentRestAPI\PizzaBundle\IntentRestAPIPizzaBundle;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IntentRestAPI\MainBundle\Entity\Produit;
use IntentRestAPI\PizzaBundle\Entity\Pizza;
use IntentRestAPI\MainBundle\Form\ProduitType;
use IntentRestAPI\PizzaBundle\Form\FoodType;

use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Produit controller.
 *
 * @Route("/produit")
 */
class ProduitController extends Controller
{

    const PRODUIT_NOT_FOUND_FROM_DB_MESSAGE = 'Erreur lors du chargement du produit. Existe t\'il ?';
    const SUPPRESSION_ACTION_SUCCESS = "Le produit a bien été supprimé de facon définitive";
    const DESACTIVATION_PRODUIT_SUCCESS = "Le produit a bien désactivé, il n'apparaitra plus sur la carte.";


    /**
     * Lists all Produit entities.
     *
     * @Route("/", name="produit")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $aProduitDatas = array();
        //TODO rendre l'appel du repo generique a toute les instances filles de Produit.
        $aoProduitDatas = $em->getRepository('IntentRestAPIPizzaBundle:Pizza')->findAll();

        foreach ($aoProduitDatas as $iKey => $oProduit) {

            $aProduitDatas[] = $oProduit->spawnInArray();
        }

        $oResponse = new JsonResponse($aProduitDatas, 200);

        return $oResponse;
    }

    /**
     * Action BackOffice.
     * Ajoute un produit a l'application.
     *
     * Pattern flux Json
     * {
     *      nom: $nomProduit,
     *      type: $typeProduit,
     *      actif: true/false,
     *      prix : $prixProduit,
     *      description: $remarqueProduit (sera valorisée lors de la commande)
     * }
     * @Route("/add", name="produit_create")
     * @Method("POST")
     */
    public function createAction(Request $oRequest)
    {
        $em = $this->getDoctrine()->getManager();
        $aProduitDatas = array('coucou' => 'toi');
        // Récupération des flux Json des produits à ajouter a la commande.
        /*$sResultRequestContent = $oRequest->getContent();
        // Ajout des produits à la commande courante.
        $aResultRequestContentArray = json_decode($sResultRequestContent, true);
        // Instanciation des nouveaux produits du flux.
        $aNewProduitsToPersist = $this->container->get('mainBundle.service.pattern.factoryProduit')
                                                 ->createAllInheritedProduct($aResultRequestContentArray, "Pizza");

        foreach ($aNewProduitsToPersist as $oProduit) {
            $em->persist($oProduit);
        }

        $aProduitDatas = array(
            "testDelete" => "testCreateProduit"
        );

        $em->flush();*/
        $oResponse = new JsonResponse($aProduitDatas, 200);
        return $oResponse;
    }

    /**
     * Displays a form to create a new Produit entity.
     *
     * Json Stream
     {
        "id":14,
        "nom": "nomProduit",
        "actif": true,
        "description": "Pizza trop bonne",
        "instance": "Pizza"
     }
     *
     *
     * ACTUELLEMENT LE FLUX FONCTIONNEL EST CELUI-CI
     * IL Y A DES INFORMATIONS INUTILES QUI DEVRAIT ETRE LIE A LA COMMANDE
     *
     * {
        "nom": "Norvegienne",
        "actif": false,
        "description": "Pizza au thon",
        "instance": "Pizza",
        "taille": "bambin",
        "type": "undefined",
        "piment": "supplement sel",
        "prix": "34",
        "device": "Euros"
        }
     *
     * @Route("/new", name="produit_new")
     * @Method("POST")
     */
    public function newAction(Request $oRequest)
    {
        $oEm = $this->getDoctrine()->getManager();
        $aJsonRequestDatas = json_decode($oRequest->getContent(), true);
        if (null == $aJsonRequestDatas) {
            throw new \Exception("Le flux Json n'est pas conforme");
        }

        $sMessageToDisplay = "Votre produit a été ajouter avec succes";
        $oProduct = MainFactory::getInstanceProduit($aJsonRequestDatas['instance']);
        // Initialisalisation dynamique des instances du Factory.
        foreach ($aJsonRequestDatas as $sAttriName => $sAttribValue) {

            if ('instance' != $sAttriName) {
                $sAttriName = ucfirst($sAttriName);
                $sOperation = "set$sAttriName";
                $oProduct->$sOperation($sAttribValue);
            }
        }

        $oProduct
            ->setNom($aJsonRequestDatas['nom'])
            ->setActif($aJsonRequestDatas['actif'])
            ->setDescription($aJsonRequestDatas['description'])
        ;

        $oEm->persist($oProduct);
        $oEm->flush();

        $aResponseConfirmationAction = array(
            "id" => $oProduct->getId(),
            "nom" => $oProduct->getNom(),
            "message" => $sMessageToDisplay
        );

        $oJsonResponse = new JsonResponse();
        $oJsonResponse->setData($aResponseConfirmationAction);
        return $oJsonResponse;
    }

    /**
     * Finds and displays a Produit entity.
     *
     * @Route("/{iId}", name="produit_show")
     * @Method("GET")
     */
    public function showAction($iId)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oProduit = $oEm->getRepository('IntentRestAPIMainBundle:Produit')->find($iId);

        if (!$oProduit) {
            throw $this->createNotFoundException(self::PRODUIT_NOT_FOUND_FROM_DB_MESSAGE);
        }

        $aProduitDatas = $oProduit->spawnInArray();
        $oResponse = new JsonResponse($aProduitDatas, 200);

        return $oResponse;
    }

    /**
     * Edits an existing Produit entity.
     *
     * {
        "id":14,
        "nom": "nomProduit",
        "actif": true,
        "description": "Pizza trop bonne",
        "instance": "Pizza"
        }
     * @Route("/{iId}", name="produit_update")
     * @Method("PUT")
     * @Template("IntentRestAPIMainBundle:Produit:edit.html.twig")
     */
    public function updateAction(Request $oResquest, $iId)
    {
        $oEm = $this->getDoctrine()->getManager();

        $oProduit = $oEm->getRepository('IntentRestAPIMainBundle:Produit')->findOneById($iId);

        if (!$oProduit) {
            throw $this->createNotFoundException(self::PRODUIT_NOT_FOUND_FROM_DB_MESSAGE);
        }

        $aDataProduitFromJson = json_decode($oResquest->getContent(), true);
        // TODO faire une instanciation dynamique de l'objet instancié en reprenant les index du flux.
        foreach ($aDataProduitFromJson as $sAttribName => $sAttribValue) {
            if ('id' != $sAttribName && 'instance' != $sAttribName) {
                $sOperation = sprintf("set%s", ucfirst($sAttribName));
                $oProduit->$sOperation($sAttribValue);
            }
        }

        $oEm->flush();
        $aProduitDatas = array(
            "id" => $oProduit->getId(),
            "nom"=> $oProduit->getNom(),
            "message"=> "Votre modification a bien ete effectue"
        );

        $oResponse = new JsonResponse($aProduitDatas, 200);

        return $oResponse;
    }

    /**
     * Deletes a Produit entity.
     *
     * @Route("/{iId}", name="produit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $oRequest, $iId)
    {
        $em = $this->getDoctrine()->getManager();
        $oProduit = $em->getRepository('IntentRestAPIMainBundle:Produit')->find($iId);

        if (!$oProduit) {
            throw $this->createNotFoundException(self::PRODUIT_NOT_FOUND_FROM_DB_MESSAGE);
        }

        $em->remove($oProduit);
        $em->flush();

        $aProduitDatas = array(
            "id" => $oProduit->getId(),
            "nom"=> $oProduit->getNom(),
            "message" => self::SUPPRESSION_ACTION_SUCCESS
        );
        $oResponse = new JsonResponse($aProduitDatas, 200);

        return $oResponse;
    }

    /**
     * Annule le produit de la commande en cours.
     * Passe le statut du produit a inactif. Celui-ci ne sera pas compris dans la commande
     * mais pourra etre visible par le BO en cas de controle ou pour les statistiques.
     *
     * @Route("/status/{iId}", name="produit_change_status")
     * @Method("PUT")
     */
    public function cancelAction(Request $oRequest, $iId)
    {
        $em = $this->getDoctrine()->getManager();
        $oProduit = $em->getRepository('IntentRestAPIMainBundle:Produit')->find($iId);

        if (!$oProduit) {
            throw $this->createNotFoundException(self::PRODUIT_NOT_FOUND_FROM_DB_MESSAGE);
        }

        $oProduit->setActif(!$oProduit->getActif());
        $em->flush();

        $aProduitDatas = array(
            "id" => $oProduit->getId(),
            "nom"=> $oProduit->getNom(),
            "message" => self::DESACTIVATION_PRODUIT_SUCCESS
        );
        $oResponse = new JsonResponse($aProduitDatas, 200);

        return $oResponse;
    }
}
