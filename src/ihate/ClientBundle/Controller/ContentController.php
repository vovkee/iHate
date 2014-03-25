<?php

namespace ihate\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ihate\CoreBundle\Controller\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Route ("/")
 */
class ContentController extends AdvancedController
{
    /**
     * @Route("/", name="inAccount")
     * @Template("ihateClientBundle:Client:inAccount.html.twig")
     */
    public function homeAction()
    {
        return array();
    }
}
