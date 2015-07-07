<?php

namespace IntentRestAPI\UserBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IntentRestAPI\UserBundle\Entity\Panier;
use IntentRestAPI\UserBundle\Form\PanierType;

/**
 * Panier controller.
 * @Route("/panier")
 */
class PanierController extends Controller
{

    /**
     * Lists all Panier entities.
     *
     * @Route("/", name="panier")
     * @Method("GET")
     */
    public function indexAction()
    {
        $oEm = $this->getDoctrine()->getManager();
        $aDataFromJson = array();

        $aoPanier = $oEm->getRepository("IntentRestAPIUserBundle:Panier")
                            ->findAll();

        if (!$aoPanier) {
            throw new Exception("Aucun panier n'a été trouvé en base de données");
        }

        foreach ($aoPanier as $oPanier) {
            $aDataFromJson[] = $oPanier->spawnInArray();
        }

        $oResponse = new JsonResponse();
        $oResponse->setData($aDataFromJson);

        return $oResponse;
    }

    /**
     * Creates a new Panier entity.
     *
     * @Route("/", name="panier_create")
     * @Method("POST")
     * @Template("IntentRestAPIUserBundle:Panier:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oOwner = $oEm->getRepository("IntentRestAPIUserBundle:MainUser")
                        ->findOneBy(array('id' => 1));

        $oPanier = new Panier();
        $oPanier
            ->setNom('superpan')
            ->setActif(true)
            ->setMontant(34)
            ->setOwner($oOwner)
        ;

        $oEm->persist($oPanier);
        $oEm->flush();

        $oResponse = new JsonResponse();
        $oResponse->setData(array(
            'id' => $oPanier->getId(),
            'message' => "Le panier a ete creer avec succes"
        ));

        return $oResponse;
    }

    /**
     * Creates a form to create a Panier entity.
     *
     * @param Panier $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Panier $entity)
    {
        $form = $this->createForm(new PanierType(), $entity, array(
            'action' => $this->generateUrl('panier_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Panier entity.
     *
     * @Route("/new", name="panier_new")
     * @Method("POST")
     * @Template()
     */
    public function newAction()
    {
        $oEm = $this->getDoctrine()->getManager();
        $oOwner = $oEm->getRepository("IntentRestAPIUserBundle:MainUser")
                        ->findOneBy(array(
                            'id' => 1
                        ))
        ;

        $oPanier = new Panier();
        $oPanier
            ->setNom('panier1')
            ->setOwner($oOwner)
            ->setMontant('890')
            ->setActif(true)
        ;

        $oEm->persist($oPanier);
        $oEm->flush();

        $aData = array(
            'idIuser' => $oPanier->getId(),
            'message' => "L'utilisateur a bien ete enregistre"
        );

        $oResponse = new JsonResponse();
        $oResponse->setData($aData);

        return $oResponse;
    }

    /**
     * Finds and displays a Panier entity.
     *
     * @Route("/{id}", name="panier_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntentRestAPIUserBundle:Panier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Panier entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Panier entity.
     *
     * @Route("/{id}/edit", name="panier_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntentRestAPIUserBundle:Panier')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Panier entity.');
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
    * Creates a form to edit a Panier entity.
    *
    * @param Panier $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Panier $entity)
    {
        $form = $this->createForm(new PanierType(), $entity, array(
            'action' => $this->generateUrl('panier_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Panier entity.
     *
     * @Route("/{id}", name="panier_update")
     * @Method("PUT")
     * @Template("IntentRestAPIUserBundle:Panier:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IntentRestAPIUserBundle:Panier')
                        ->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Panier entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('panier_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Panier entity.
     *
     * @Route("/{id}", name="panier_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntentRestAPIUserBundle:Panier')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Panier entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('panier'));
    }

    /**
     * Creates a form to delete a Panier entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('panier_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
