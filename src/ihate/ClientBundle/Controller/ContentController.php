<?php

namespace ihate\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ihate\CoreBundle\Controller\AdvancedController;
use Symfony\Component\Security\Core\SecurityContext;
use ihate\CoreBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
        $user = $this->get('security.context')->getToken()->getUser();
        $name = $user->getName();
        return array('name' => $name);
    }

    /**
     * @Route ("/search", name="search")
     * @Template("ihateClientBundle:Client:search.html.twig")
     */
    public function searchAction(Request $request)
    {
        $search = $this->get('request')->request->get('search');
        $userRepository = $this->getUserRepository();
        $result = $userRepository->search($search);
        return array(
            'search' => $search,
            'result' => $result
        );
    }
}
