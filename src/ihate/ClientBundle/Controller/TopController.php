<?php

namespace ihate\ClientBundle\Controller;

use ihate\CoreBundle\Entity\Comment;
use ihate\CoreBundle\Entity\Hate;
use ihate\CoreBundle\Form\Type\CommentType;
use ihate\CoreBundle\Form\Type\HateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ihate\CoreBundle\Controller\AdvancedController;
use Symfony\Component\Security\Core\SecurityContext;
use ihate\CoreBundle\Form\Type\PostType;
use ihate\CoreBundle\Form\Type\UserType;
use ihate\CoreBundle\Entity\User;
use ihate\CoreBundle\Entity\Post;
use ihate\CoreBundle\Entity\Country;
use ihate\ClientBundle\Controller\ContentController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class TopController extends AdvancedController
{
    public function postTopAction()
    {
        $repository = $this->getPostRepository();
        $top = $repository->getPostTop($this->getUser());
        return $this->render(
            'ihateClientBundle:PageStructure:postTop.html.twig',
            array('top' => $top)
        );
    }

    public function userTopAction()
    {
        $repository = $this->getUserRepository();
        $userTop = $repository->getUserTop($this->getUser());
        return $this->render(
            'ihateClientBundle:PageStructure:userTop.html.twig',
            array('userTop' => $userTop)
        );
    }
}