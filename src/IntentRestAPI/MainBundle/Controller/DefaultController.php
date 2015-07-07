<?php

namespace IntentRestAPI\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @IgnoreAnnotation("View")
 */
class DefaultController
{

    /**
     * @View()
     * @Route("/testrest", name="main_route")
     */
    public function index2Action()
    {
        $view = View::create();
        $data = array('foo', 'pok');
        $view->setData($data);
        return $view;
    }

    /**
     * @Route("/", name="main")
     * @template()
     */
    public function indexAction()
    {
        //return $this->render('default/index.html.twig');

        $aData = array('name' => "Brau", 'prenom' => "Laurent");

        $oResponse = new JsonResponse();
        $oResponse->setData($aData);
        $oResponse->setStatusCode(201);

        return $oResponse;
    }
}
