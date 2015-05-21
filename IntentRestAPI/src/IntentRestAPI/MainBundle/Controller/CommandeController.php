<?php

namespace IntentRestAPI\MainBundle\Controller;

use IntentRestAPI\MainBundle\Entity\Produit;
use IntentRestAPI\PizzaBundle\Entity\Food;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IntentRestAPI\MainBundle\Entity\Commande;
use IntentRestAPI\MainBundle\Form\CommandeType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Commande controller.
 *
 * @Route("/commande")
 */
class CommandeController extends Controller
{

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
     * Creates a new Commande entity.
     *
     * @Route("/", name="commande_create")
     * @Method("POST")
     * @Template("IntentRestAPIMainBundle:Commande:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Commande();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('commande_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Commande entity.
     *
     * @param Commande $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Commande $entity)
    {
        $form = $this->createForm(new CommandeType(), $entity, array(
            'action' => $this->generateUrl('commande_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Commande entity.
     *
     * @Route("/new", name="commande_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Commande();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
    * Creates a form to edit a Commande entity.
    *
    * @param Commande $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Commande $entity)
    {
        $form = $this->createForm(new CommandeType(), $entity, array(
            'action' => $this->generateUrl('commande_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
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
     * @Route("/ajax/command/update", name="update_order_ajax")
     */
    public function updateCommandeAjax(Request $oRequest) {

        // TODO changer la table de liaison produit_commande pour ajouter les champs date et quantité (voir le traitement choisi).
        $oEm = $this->getDoctrine()->getManager();
        $oProduitRepository = $oEm->getRepository('IntentRestAPIMainBundle:Produit');
        $oCommandeRepository = $oEm->getRepository('IntentRestAPIMainBundle:Commande');
        $aDatasCmd = array();
        $iIdProduit = 2;
        $iIdCommande = 20;

        $oProduit = $oProduitRepository->find($iIdProduit);
        $oCommande = $oCommandeRepository->find($iIdCommande);

        if ($oRequest->isXmlHttpRequest()) {

            $aDatasCmd['commande'] = "donnees mixtes sur la commande";
            $aDatasCmd['statusCode'] = 200;

            $oProduit = $oProduitRepository->find($iIdProduit);
        }

        $oCommande->addProduit($oProduit);
        $oEm->persist($oCommande);
        $oEm->flush();

        $aDatasTest = array (
            "datas" => "udate commande : datas sur la commande",
            "Message" => "script a enlever une fois que les requetes isXmlHttpRequest seront pretes",
            "statusCode" => 200
        );

        $oResponse = new JsonResponse($aDatasTest, 200);

        return $oResponse;
    }

    /**
     * Ouvre une commande et ajoute la selection
     * Cette methode doit fonctionner avec un listener JS qui envoi
     * une requete AJAX apres la premiere selection de l'utilisateur.
     *
     * @Route("/ajax/command/add", name="open_order_ajax")
     */
    public function openCommandeAjax(Request $oRequest) {

        $oEm = $this->getDoctrine()->getManager();
        $oProduitRepository = $oEm->getRepository('IntentRestAPIMainBundle:Produit');
        $aDatasCmd = array();
        $iIdProduit = 2;

        $oProduit = $oProduitRepository->find($iIdProduit);
        //var_dump("<PRE>",$oProduit);
        if ($oRequest->isXmlHttpRequest()) {
            $aDatasCmd['commande'] = "donnees mixtes sur la commande";
            $aDatasCmd['statusCode'] = 200;

            $oProduit = $oProduitRepository->find($iIdProduit);
            //var_dump($oProduit);
        }

        $oCommandeTest = new Commande();
        $oCommandeTest->setNom("CommandeAddedTest");
        $oCommandeTest->setActif(true);
        $oCommandeTest->addProduit($oProduit);

        //var_dump("<PRE>",$oCommandeTest->getProduits()->get(1)->getId());
        $oEm->persist($oCommandeTest);
        $oEm->flush();

        $aDatasTest = array (
            "datas" => "add commande : datas sur la commande",
            "Message" => "script a enlever une fois que les requetes isXmlHttpRequest seront pretes",
            "statusCode" => 200
        );

        $oResponse = new JsonResponse($aDatasTest, 200);

        return $oResponse;
    }

    /*
    *   methode destiné a etre un service
    *   Parcours un objet recursivement et transforme l'objet
    *   et tout les objets liés en array.
    */
    private function explodeObjectToArray(Object $oObj) {

        if (is_object($oObj)) {
            // Parcours tout les champs de l'objet.
            // Lorqu'un champ est une liaison objet, elle recursive
            foreach ($oObj as $sField) {
                if (is_object($sField)) {
                    $aObjLinked = $this->explodeObjectToArray($sField); // C'est juste le concept.
                    $aObj = $aObjLinked;
                }
                else {
                    $aObj['fieldName'] = "fieldValue";
                }
            }

            return $aObj;
        }
    }
}
