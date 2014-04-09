<?php
namespace ihate\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use ihate\CoreBundle\Manager\UserManager;
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
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getUserRepository()
    {
        return $this->getDoctrine()->getManager()
            ->getRepository('ihateCoreBundle:User');
    }
    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
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
}