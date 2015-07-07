<?php

namespace IntentRestAPI\MainBundle\Controller;

use IntentRestAPI\MainBundle\Entity\Produit;
use IntentRestAPI\MainBundle\Entity\ProduitCommande;
use IntentRestAPI\MainBundle\Entity\User;
use IntentRestAPI\MainBundle\Factory\MainFactory;
use IntentRestAPI\PizzaBundle\Entity\Food;
use IntentRestAPI\PizzaBundle\Entity\Pizza;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IntentRestAPI\MainBundle\Entity\Commande;
use IntentRestAPI\MainBundle\Form\CommandeType;
use Symfony\Component\HttpFoundation\Response;

use IntentRestAPI\MainBundle\Factory\AbstractFactory;

/**
 * Commande controller.
 *
 * @Route("/commande")
 */
class CommandeController extends Controller
{
    const COMMAND_NOT_FOUND_IN_DATABASE = "La commande ne semble pas exister en base de données";
    const COMMAND_ACTION_SUCCESS = "Le changement effectué a bien ete pris en compte";

    /**
     * Lists all Commande entities.
     *
     * @Route("/", name="commande")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IntentRestAPIMainBundle:Commande')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Commande entity.
     *
     * @Route("/{id}", name="commande_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IntentRestAPIMainBundle:Commande')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Commande entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Commande entity.
     *
     * @Route("/{id}/edit", name="commande_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntentRestAPIMainBundle:Commande')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Commande entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Commande entity.
     *
     * @Route("/{id}", name="commande_update")
     * @Method("PUT")
     * @Template("IntentRestAPIMainBundle:Commande:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IntentRestAPIMainBundle:Commande')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Commande entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('commande_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Commande entity.
     *
     * @Route("/{id}", name="commande_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntentRestAPIMainBundle:Commande')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Commande entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('commande'));
    }

    /**
     * Creates a form to delete a Commande entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commande_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    // #################################################################################################################
    //                                             METHODES AJAX
    // #################################################################################################################

    /**
     * Ouvre une commande et ajoute la selection
     * Cette methode doit fonctionner avec un listener JS qui envoi
     * une requete AJAX apres la premiere selection de l'utilisateur.
     *
     *
     * {
        "headerInfos": "Fichier",
        "commandes":
        [
            {
            "nom": "brololo",
            "actif": true,
            "description": "Remplacer les olives noirs par des olives vertes",
            "date_creation": "2/06/15 : 19h23"
            },
            {
            "produits":
                [
                    {
                    "taille":"banbina",
                    "type":"Pizza",
                    "piment":"sans",
                    "id":4,
                    "nom":"cutommane",
                    "quantite": 3,
                    "actif":true,
                    "device":"euros",
                    "prix":"4",
                    "description":"customDescriptions"
                    },
                    {
                    "taille":"Lutin",
                    "type":"Pizza",
                    "piment":"cayenne",
                    "id":3,
                    "nom":"cutommane",
                    "quantite": 1,
                    "actif":true,
                    "device":"dollars",
                    "prix":"5",
                    "description":"customDescriptions"
                    }
                ]
            }
        ]
     }
     *
     * @Route("/ajax/add", name="open_order_ajax")
     * @Method("POST")
     */
    public function createCommandeAjax(Request $oRequest) {

        $oEm = $this->getDoctrine()->getManager();

        $sCommandRequestContentJson = $oRequest->getContent();
        $aCommandRequestContent = json_decode($sCommandRequestContentJson, true);
        // Conversion du flux Json en Array
        $oCommande = new Commande();
        $oCommande->setPrix(23);
        $oCommande->setRemise(0);
        // TODO verifier la gestion d'erreurs possible sur le flux des nouvelles commandes. 
        $oCommande->setNom($aCommandRequestContent['commandes'][0]['nom']);
        $oCommande->setActif($aCommandRequestContent['commandes'][0]['actif']);
        $oCommande->setRemarque($aCommandRequestContent['commandes'][0]['description']);
        $oProduitRepository = $oEm->getRepository('IntentRestAPIMainBundle:Produit');
        $oCommande->setDate(date(Commande::API_DATE_FORMAT));
        // TODO pour la version de demonstration nous feront que des comptes utilisateurs permanents.
        /*
         * l'idée est de creer une classe derivé d'utilisateur temporaire si le user n'est pas reconnu en base.
         * Le type user temporaire sera dériver de User et sera remove la fin de la commande.
         * Le nom de l'utilisateur sera conservé dans l'historique avant la suppression.
         */
        // TODO lbrau : methode gerant le type d'utilsateur connecté, retourne un user de type User, mais instancié avec la bonne classe.
        $oCurrentUser = $this->getRightUserType($aCommandRequestContent['commandes'][0]['nom']);
        $oCommande->setUser($oCurrentUser);

        // Ajout du ou des produits a ajouté dans la nouvelle commande.
        foreach ($aCommandRequestContent['commandes'][1]['produits'] as $iKey => $aProduct) { // TODO gestion d'erreur si le flux est ABS ou NC

            $oProduit = $oProduitRepository->find($aProduct['id']); // TODO gestion d'erreur en cas de produits innexistant
            $oCollectionLink = new ProduitCommande();
            $oCollectionLink
                ->setCommentaires($aProduct['description'])
                ->setDate(date(Commande::API_DATE_FORMAT))
                ->setTaille($aProduct['taille'])
                ->setQuantite($aProduct['quantite'])
                ->setPiment($aProduct['piment'])
                ->setProduits($oProduit)
                ->setCommandes($oCommande);
        }

        $oEm->persist($oCollectionLink);
        $oEm->flush();

        $aCommandeDatas = $oCommande->spawnInArray();
        $oResponse = new JsonResponse($aCommandeDatas, 200);

        return $oResponse;
    }

    private function getRightUserType($mUser) {

        // TODO lbrau : pour le moment retourne un user de type User
        // Devra ensuite retourné un user de type temporaire ou permanent.
        $oEm = $this->getDoctrine()->getManager();
        $oUser = $oEm->getRepository("IntentRestAPIMainBundle:User")
                            ->findOneBy(array('nom' => $mUser));
        ;

        if (null == $oUser) {
            $oUser = new User();
            // TODO lbrau : voir pour l'initialisation des attributs.
            /*
             * Il faudrait qu'uniquement les attributs de la classe fille soit definisable.
             * Il est possible qu'il faille meme une classe bien distinct des users. Rsg.
             */
        }

        return $oUser;
    }

    /**
     * Ouvre une commande et ajoute la selection
     * Cette methode doit fonctionner avec un listener JS qui envoi
     * une requete AJAX apres la premiere selection de l'utilisateur.
     *
     * @Route("/ajax/command/read", name="read_order_ajax")
     * @Method({"GET", "POST"})
     */
    public function readCommandeAjax(Request $oRequest) {

        $oEm = $this->getDoctrine()->getManager();
        $oProduitRepository = $oEm->getRepository('IntentRestAPIMainBundle:Produit');
        $aDatasCmd = array();
        $iIdProduit = 2;

        $oProduit = $oProduitRepository->find($iIdProduit);
        $this->hydrateProduitTest($oProduit);

        $oCommandeTest = new Commande();
        $oCommandeTest->setNom("CommandeAddedTest");
        $oCommandeTest->setRemarque("Sans oignons et supplément fromage");
        $oCommandeTest->setActif(true);
        $oCommandeTest->addProduit($oProduit);
        $oEm->persist($oCommandeTest);
        $oEm->flush();

        $aCommandeDatas = $oCommandeTest->spawnInArray();
        $oResponse = new JsonResponse($aCommandeDatas, 200);

        return $oResponse;
    }

    /**
     * Ouvre une commande existante et ajoute la selection
     * Cette methode doit fonctionner avec un listener JS qui envoi
     * une requete AJAX apres la premiere selection de l'utilisateur.
     *
     * @Route("/ajax/update", name="update_order_ajax")
     * @Method("PUT")
     */
    public function updateCommandeAjax(Request $oRequest) {

        $oEm = $this->getDoctrine()->getManager();
        $oProduitRepository = $oEm->getRepository('IntentRestAPIMainBundle:Produit');
        $oCommandeRepository = $oEm->getRepository('IntentRestAPIMainBundle:Commande');
        $aDatasCmd = json_decode($oRequest->getContent(), true);
        $aProduitsFromJson = $aDatasCmd['commandes'][1]['produits'];
        $iIdCommande = $aDatasCmd['commandes'][0]['id'];
        $oCommande = $oCommandeRepository->find($iIdCommande);
        if (!$oCommande) {
            throw $this->createNotFoundException(self::COMMAND_NOT_FOUND_IN_DATABASE);
        }

        foreach ($aProduitsFromJson as $mKey=> $aProduit) {

            $iIdProduit = $aProduit['id'];
            $oProduit = $oProduitRepository->find($iIdProduit);

            $oCollectionLink = new ProduitCommande();
            $oCollectionLink
                ->setCommentaires($aProduit['description'])
                ->setDate(date(Commande::API_DATE_FORMAT))
                ->setQuantite($aProduit['quantite'])
                ->setProduits($oProduit)
                ->setCommandes($oCommande);
        }

        $oEm->persist($oCollectionLink); // TODO inutile lors d'un update.
        $oEm->flush();

        $aCommandeDatas = $oCommande->spawnInArray();
        $oResponse = new JsonResponse($aCommandeDatas, 200);

        return $oResponse;
    }

    /**
     * Supprime une commande de la base
     *
     * @param Request $oRequest
     * @param $iIdCommandeToDelete
     * @Route("/ajax/command/delete", name="delete_order_ajax")
     * @Method("DELETE")
     */
    public function deleteCommande(Request $oRequest, $iIdCommandeToDelete = null) {

        $iIdCommandeToDelete = 1;
        $oEm = $this->getDoctrine()->getManager();
        $oCommande = $oEm->getRepository("IntentRestAPIMainBundle:Commande")
                            ->find($iIdCommandeToDelete);

        $oEm->remove($oCommande);
        $aCommandeDatas = array("testDelete" => "dataTestDelete");
        $oResponse = new JsonResponse($aCommandeDatas, 200);

        return $oResponse;
    }

    /**
     * Annule une commande passée.
     *
     * @param Request $oRequest
     * @param $iIdCommandeToCancel
     *
     * @Method({"POST", "GET"})
     * @Route("/ajax/command/cancel", name="cancel_order_ajax")
     */
    public function cancelCommande(Request $oRequest, $iIdCommandeToCancel = null) {

        $iIdCommandeToCancel = 20; // TODO données de tests
        $oEm = $this->getDoctrine()->getManager();
        $oCommande = $oEm->getRepository("IntentRestAPIMainBundle:Commande")
                            ->find($iIdCommandeToCancel);

        $oCommande->setActif(!$oCommande->getActif());

        $aCommandeDatas = array("testCancel" => "dataTestcancel");
        $oResponse = new JsonResponse($aCommandeDatas, 200);

        return $oResponse;
    }

    private function hydrateProduitTest(Produit $oProduit) {
        $oProduit->setNom("cutommane");
        $oProduit->setActif(true);
        $oProduit->setDescription("customDescriptions");
    }

    /**
     * Change l'etat de la commande iId par l'etat present dans le flux.
     *
     * {
        "id": 15,
        "statToChange": "en_preparation"
        }
     * @Method("PUT")
     * @Route("/ajax/command/change/stat/{iIdCommandeToChange}", name="change_stat_command_ajax")
     */
    public function changeCommandStat(Request $oRequest, $iIdCommandeToChange = null) {

        $oEm = $this->getDoctrine()->getManager();
        $oCommande = $oEm->getRepository("IntentRestAPIMainBundle:Commande")
                            ->find($iIdCommandeToChange);

        if (!$aDataFromJsonRequest = json_decode($oRequest->getContent(), true)) {
            throw new Exception("Le flux Json semble incorrect");
        }

        if (!$oCommande) {
            throw new Exception(self::COMMAND_NOT_FOUND_IN_DATABASE);
        }
        $oCommande->setEtat($aDataFromJsonRequest['statToChange']);
        $aCommandeDatas = array(
            "id"=> $oCommande->getId(),
            "nom" => $oCommande->getNom(),
            "message" => self::COMMAND_ACTION_SUCCESS
        );

        $oEm->persist($oCommande); // TODO inutile lors d'un update
        $oEm->flush();

        $oResponse = new JsonResponse($aCommandeDatas, 200);

        return $oResponse;
    }


    /**
     * Change l'etat de la commande iId par l'etat present dans le flux.
     *
     * {
        "id": 15,
        "statToChange": "en_preparation",
        "client": 12
        }
     * @Method("PUT")
     * @Route("/ajax/command/ended/{iIdCommandeToChange}", name="ended_command_ajax")
     */
    public function CommandeTerminee(Request $oRequest, $iIdCommandeToChange) {

        $oEm = $this->getDoctrine()->getManager();
        $aJsonContent = json_decode($oRequest->getContent(), true);

        $oCommande = $oEm->getRepository("IntentRestAPIMainBundle:Commande")
                            ->findOneBy(array('id'=>$iIdCommandeToChange))
        ;


        $oDestinataire = $oEm->getRepository("IntentRestAPIMainBundle:User")
                                ->findOneBy(array(
                                    'id'=> $aJsonContent['client']
                                    )
                                )
        ;

        $oCommande->setUser($oDestinataire); // TODO lbrau : le set du user doit se faire lors de la creation de la commande. cette ligne est juste pour le test.

        $oCommande->setEtat('end');
        // TODO envoi d'une notification au staff, client, et selon les cas livreur.
        $oNotification = $this->container->get('intent_rest_api_user.notification')
                                ->SendNotification($oDestinataire);
        ;

        $oEm->persist($oNotification);
        $oEm->flush();

        $oResponse = new JsonResponse();
        $oResponse->setData(
            array(
            'id' => $oCommande->getId(),
            'message' => "La commande est passée au statut terminée"
            )
        );

        return $oResponse;
    }


    /**
     * Methodes d'intanciation d'une serie produit pour les tests.
     */
    public function addproduitTest (){

        $oEm = $this->getDoctrine()->getManager();
        // Creation des produits des tests.
        $p = new Pizza();
        $p->setNom("Queen");
        $p->setDescription("La classique");
        $p->setActif(true);
        $p->setDevice("Euros");
        $p->setPrix("12");
        $p->setPiment("sans");
        $p->setTaille("banbino"); // TODO revoir la coherence. La taille devrait etre dans la l'action de la commande.
        $p->setType("pizza");

        $z = new Pizza();
        $z->setNom("Quatre Fromage");
        $z->setDescription("full Cheeze");
        $z->setActif(true);
        $z->setDevice("Euros");
        $z->setPrix("78");
        $z->setPiment("sans");
        $z->setTaille("banbino");
        $z->setType("pizza");

        $b = new Pizza();
        $b->setNom("Andalouse");
        $b->setDescription("descriptif custom test");
        $b->setActif(true);
        $b->setDevice("Euros");
        $b->setPrix("20");
        $b->setPiment("sans");
        $b->setTaille("banbino");
        $b->setType("pizza");

        $y = new Pizza();
        $y->setNom("Calzone");
        $y->setDescription("descriptif custom test");
        $y->setActif(true);
        $y->setDevice("Euros");
        $y->setPrix("16");
        $y->setPiment("sans");
        $y->setTaille("banbino");
        $y->setType("pizza");

        $x = new Pizza();
        $x->setNom("Montagnarde");
        $x->setDescription("descriptif custom test");
        $x->setActif(true);
        $x->setDevice("Euros");
        $x->setPrix("12");
        $x->setPiment("sans");
        $x->setTaille("banbino");
        $x->setType("pizza");

        $oEm->persist($p);
        $oEm->persist($z);
        $oEm->persist($y);
        $oEm->persist($x);
        $oEm->persist($b);
    }
}
