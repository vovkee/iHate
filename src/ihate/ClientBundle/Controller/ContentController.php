<?php

namespace ihate\ClientBundle\Controller;

use ihate\CoreBundle\Entity\Hate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ihate\CoreBundle\Controller\AdvancedController;
use Symfony\Component\Security\Core\SecurityContext;
use ihate\CoreBundle\Form\Type\PostType;
use ihate\CoreBundle\Form\Type\UserType;
use ihate\CoreBundle\Entity\User;
use ihate\CoreBundle\Entity\Post;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route ("/")
 */
class ContentController extends AdvancedController
{
    /**
     * @Route("/{page}", name="inAccount",requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Template("ihateClientBundle:Client:inAccount.html.twig")
     */
    public function homeAction(Request $request, $page)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        $perPage = $this->getPerPage();
        $hate = new Hate();
        $user = $this->getUser();
        $name = $user->getName();
        $surname = $user->getSurname();
        $repository = $this->getPostRepository();
        $posts = $repository->getByUserAndFollowers($user, $page, $perPage);
        $postsCount = $repository->getCountByUserAndFollowers($user);
        //var_dump($postsCount);die();
        $path = $user->showImage();
        return array(
            'page'      => $page,
            'name'      => $name,
            'surname'   => $surname,
            'path'      => $path,
            'posts'     => $posts,
            'hate'      => $hate,
            'pages'=> ceil($postsCount/$perPage)
        );
    }
}
