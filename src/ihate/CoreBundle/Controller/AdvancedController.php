<?php
namespace ihate\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use ihate\CoreBundle\Manager\UserManager;
use ihate\CoreBundle\Repository\PostRepository;
use ihate\CoreBundle\Repository\UserRepository;
use ihate\CoreBundle\Repository\CommentRepository;
use ihate\CoreBundle\Repository\HateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Advanced controller.
 */
class AdvancedController extends Controller
{
    /**
     * @return EntityManager
     */
    public function em()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('ihateCoreBundle:User');
    }
    /**
     * @return PostRepository
     */
    public function getPostRepository()
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('ihateCoreBundle:Post');
    }

    /**
     * @return UserManager
     */
    public function getUserManager()
    {
        return $this->get('ihate.manager.user');
    }

    /**
     * @return CommentRepository
     */
    public function getCommentRepository()
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('ihateCoreBundle:Comment');
    }
    /**
     * @return HateRepository
     */
    public function getHateRepository()
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('ihateCoreBundle:Hate');
    }
}