<?php
namespace ihate\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
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
}