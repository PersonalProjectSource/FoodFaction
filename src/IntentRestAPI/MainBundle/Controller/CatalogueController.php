<?php

namespace IntentRestAPI\MainBundle\Controller;

use IntentRestAPI\MainBundle\Services\ClassTools;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use IntentRestAPI\MainBundle\Entity\Repository\ProduitRepository;
use IntentRestAPI\MainBundle\Entity\Catalogue;
use IntentRestAPI\MainBundle\Form\CatalogueType;
use Symfony\Component\HttpFoundation\Response;


/**
 * Catalogue controller.
 *
 * @Route("/catalogue")
 */
class CatalogueController extends Controller
{

    /**
     * Display all catalogues.
     *
     * @Route("/", name="catalogue2")
     * @Method("GET")
     */
    public function displayAction($iId = 1)
    {
        $em = $this->getDoctrine()->getManager();
        $aoCatalogue = $em->getRepository('IntentRestAPIMainBundle:Catalogue')
                                ->findAll();

        if (!$aoCatalogue) {
            throw $this->createNotFoundException('Aucun catalogue trouvé en base');
        }

        //$aProduitDatas['nom'] = $aoCatalogue->getNom();
        //$aProduitDatas['id'] = $aoCatalogue->getId();
        foreach ($aoCatalogue as $iKey => $oCatalogue) {
            $aCatalogues[] = $oCatalogue->spawnInArray();
        }

        header('Access-Control-Allow-Origin: *');
        $oResponse = new JsonResponse($aCatalogues, 200);
        return $oResponse;
    }

    /**
     * Lists all Catalogue entities.
     *
     * @Route("/choice", name="catalogue_choice")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $oEm = $this->getDoctrine()->getManager();
        $oCatalogue = $oEm->getRepository("IntentRestAPIMainBundle:Catalogue")
                                ->findOneBy(array('id' => 5))
        ;

        $aoProduitsCatalogue = $oCatalogue->getProduits();

        return array(
            'entities' => $aoProduitsCatalogue,
        );
    }

    /**
     * Lists all Catalogue entities.
     *
     * @Route("/none", name="catalogue")
     * @Method("GET")
     * @Template()
     */
    public function index2Action()
    {

        return new Response();
        /*
        $em = $this->getDoctrine()->getManager();
        $aCataloguesDatas = array();
        $aoCatalogueDatas = $em->getRepository('IntentRestAPIMainBundle:Catalogue')->findAll();

        foreach ($aoCatalogueDatas as $iKey => $oCatalogue) {
            $aCataloguesDatas[] = $oCatalogue->spawnInArray();
        }

        $oResponse = new JsonResponse($aCataloguesDatas, 200);
        return $oResponse;*/
    }

    /**
     * Methode de test pour hydrater le catalogue de produits.
     *
     * @param Catalogue $oCatalogue
     * @param array $aProduits
     * @param Boolean $bDebug
     */
    private function hydrateCatalogue(Catalogue $oCatalogue, &$iNbProdcut = 0, Array $aProduits = array(), $bDebug = false) {

        // TODO faire une gestion d'erreurs sur les parametres de la methode.
        $oEm = $this->getDoctrine()->getManager();
        if (null != $oCatalogue) {

            if (true == $bDebug) {
                $aProduits = $oEm->getRepository("IntentRestAPIMainBundle:Produit")
                    ->getAllActifProduct();
            }
            elseif (0 == count($aProduits)) {
                $aProduits = $oEm->getRepository("IntentRestAPIMainBundle:Produit")
                    ->getAllActifProduct();
            }

            foreach ($aProduits as $oProduit) {
                $iNbProdcut++;
                $oCatalogue->addProduit($oProduit);
            }
        }
        else {
            throw new Exception("Impossible d'hydrater ce catalogue");
        }

        $oEm->persist($oCatalogue);
        $oEm->flush();

        return true;
    }

    /**
     * Creer un nouveau catalogue et le persist en base de données.
     *
     * Format jsonStream:
     *
      {
        "nom": "Catalogue de l'ete",
        "actif": true
      }
     *
     * @Route("/new", name="catalogue_new")
     * @Method("POST")
     */
    public function newAction(Request $oRequest)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oCatalogue = new Catalogue();
        $aRequestContent = json_decode($oRequest->getContent(),true);
        $oCatalogue->setNom($aRequestContent['nom']);
        $oCatalogue->setActif($aRequestContent['actif']);
        $aDataToJson = array();

        $oEm->persist($oCatalogue);
        $oEm->flush();

        $aDataToJson['nom'] = $aRequestContent['nom'];
        $aDataToJson['message'] = "Le catalogue a bien ete cree";
        $oReponse = new JsonResponse();
        $oReponse->setData($aDataToJson);

        return $oReponse;
    }

    /**
     * Finds and displays a Catalogue entity.
     *
     * @Route("/{iId}", name="catalogue_show")
     * @Method("GET")
     */
    public function showAction($iId = 1)
    {
        $em = $this->getDoctrine()->getManager();
        $aProduitDatas = array();
        $oCatalogue = $em->getRepository('IntentRestAPIMainBundle:Catalogue')
                            ->find($iId);

        if (!$oCatalogue) {
            throw $this->createNotFoundException('Aucun catalogue trouvé en base');
        }

        // TODO Methode de test pour hydratation du catalogue
        if (0 == count($oCatalogue->getProduits())) {
            $this->hydrateCatalogue($oCatalogue);
        }
        // ###################################################

        // Generation du flux Json sortant
        $aProduitDatas['nom'] = $oCatalogue->getNom();
        $aProduitDatas['id'] = $oCatalogue->getId();
        foreach ($oCatalogue->getProduits() as $iKey => $oProduit) {
            $aProduitDatas['produits'][] = $oProduit->spawnInArray();
        }

        header('Access-Control-Allow-Origin: *');
        $oResponse = new JsonResponse($aProduitDatas, 200);
        return $oResponse;
    }

    /**
     * Edits an existing Catalogue entity.
     *
     * @Route("/{iId}", name="catalogue_update")
     * @Method("PUT")
     */
    public function updateAction(Request $oRequest, $iId)
    {
        $oEm = $this->getDoctrine()->getManager();

        $oCatalogue = $oEm->getRepository('IntentRestAPIMainBundle:Catalogue')->find($iId);

        if (!$oCatalogue) {
            throw $this->createNotFoundException('Unable to find Catalogue entity.');
        }

        $aRequestContent = json_decode($oRequest->getContent(),true);

        $oCatalogue->setNom($aRequestContent['nom']);
        $oCatalogue->setActif($aRequestContent['actif']);
        $aDataToJson = array();

        $oEm->persist($oCatalogue);
        $oEm->flush();

        $aDataToJson['id'] = $oCatalogue->getId();
        $aDataToJson['nom'] = $aRequestContent['nom'];
        $aDataToJson['message'] = "Le catalogue a bien ete modifie";
        $oReponse = new JsonResponse();
        $oReponse->setData($aDataToJson);

        return $oReponse;
    }

    /**
     * Ajout un ou plusieurs produits a un catalogue existant.
     *
     *
     * Json Stream:
     * [
        {
        "id":"15",
        "nom":"NomProduct"
        },
        {
        "id":"16",
        "nom":"NomProduct"
        }
       ]
     *
     * @Route("/addProduct/{iIdCatalog}", name="catalogue_add_product")
     * @Method("POST")
     */
    public function addProductsToCatalog(Request $oRequest, $iIdCatalog) {

        $oCatalog = $this->getDoctrine()->getManager()
                            ->getRepository("IntentRestAPIMainBundle:Catalogue")
                                ->findOneBy(array('id'=> $iIdCatalog));

        $atabTestProd = json_decode($oRequest->getContent(), true);
        $aProduits = $this->instanciateObjectsProductsFromArray($atabTestProd);

        $aJsonReturnData = array();
        $aJsonReturnData['nom'] = $oCatalog->getNom();

        if (true == $this->hydrateCatalogue($oCatalog, $iNbProduct, $aProduits)) {
            $cPluriel = ($iNbProduct > 1) ? "s" : "";
            $aJsonReturnData['message'] =  sprintf("Les %d ont été ajoute%s avec succes", $iNbProduct, $cPluriel);
        }
        else {
            $aJsonReturnData['message'] =  "Echec de l'operation";
        }

        $oJsonResponse = new JsonResponse();
        $oJsonResponse->setData($aJsonReturnData);

        return $oJsonResponse;
    }

    /**
     * Transforme un tableau de données du modele(Produit) en objet du modele (entity Produit)
     *
     * @param $aData
     * @return array
     */
    private function instanciateObjectsProductsFromArray($aData) {

        $aoProduits = array();
        foreach ($aData as $aProduit) {
            $aoProduits[] = $this->getDoctrine()->getManager()
                                    ->getRepository("IntentRestAPIMainBundle:Produit")
                                        ->findOneBy(array("id" => $aProduit['id']));
        }

        return $aoProduits;
    }

    /**
     * Deletes a Catalogue entity.
     *
     * Json Stream in
     * {
     *      "produit":
     *      {
     *          "id":15,
     *          "nom":"nomProduit",
     *          "actif":true
     *      }
     * }
     *
     * @Route("/delete/produit/{iIdCatlog}", name="catalogue_delete_produit")
     * @Method("DELETE")
     */
    public function deleteProductsFromCatalogAction(Request $oRequest, $iIdCatlog)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oCatalogue = $oEm->getRepository('IntentRestAPIMainBundle:Catalogue')->find($iIdCatlog);
        $aRequestData = json_decode($oRequest->getContent(),true);

        //$aoProduits = $this->instanciateObjectsProductsFromArray($aRequestData['produits']);
        $aoProduits = $this->instanciateObjectsProductsFromArray($aRequestData);

        // TODO la suppression d'un produit du catalogue fonctionne bien
        // TODO reste a faire le reste des tests : envoi de plusieurs produits pour suppression du catalogue
        // TODO et pourquoi pas faire aussi une désactivation du catalogue (rupture de stock ...).
        foreach ($aoProduits as $oProduit) {
            $oCatalogue->removeProduit($oProduit);
        }

        $aDataToReturn = array(
            "nom" => $oCatalogue->getNom(),
            "message" => "Le produit a ete retiré du catalogue avec succes"
        );

        if (!$oCatalogue) {
            throw $this->createNotFoundException('Le catalague n\'a pas ete trouve');
        }

        $oEm->flush();
        $oJsonResponse = new JsonResponse();
        $oJsonResponse->setData($aDataToReturn);

        return $oJsonResponse;
    }

    /**
     * Retire un le produit en parametre du calatalogue en parametre.
     *
     *
     */
    private function removeOneProductFromCatalog(Catalogue $oCatalog, Produit $oProduct) {


    }

    /**
     * Deletes a Catalogue entity.
     *
     * @Route("/{iId}", name="catalogue_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $oRequest, $iId)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oCatalogue = $oEm->getRepository('IntentRestAPIMainBundle:Catalogue')->find($iId);

        $aDataToReturn = array(
            "nom" => $oCatalogue->getNom(),
            "message" => "Le catalogue a ete supprime avec succes"
            );

        if (!$oCatalogue) {
            throw $this->createNotFoundException('Le catalague n\'a pas ete trouve');
        }

        $oEm->remove($oCatalogue);
        $oEm->flush();

        $oJsonResponse = new JsonResponse();
        $oJsonResponse->setData($aDataToReturn);

        return $oJsonResponse;
    }
}
