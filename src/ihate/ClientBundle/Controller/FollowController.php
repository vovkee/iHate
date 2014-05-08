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
class FollowController extends AdvancedController
{
    /**
     * @Route ("/search", name="search")
     * @Template("ihateClientBundle:Content:search.html.twig")
     */
    public function searchAction(Request $request)
    {
        $search = $request->get('search', '');
        $user   = $this->getUser();
        if($search != NULL){
            $userRepository = $this->getUserRepository();
            $users = $userRepository->search($search, $user);
            return array(
                'search'    => $search,
                'users'     => $users
            );
        }
        else{
            return array(
                'search' => $search,
                'users'  => ''
            );
        }
    }
    /**
     * @Route ("/follow/{id}", name="follow",requirements={"id" = "\d+"})
     * @Template()
     */
    public function followAction(Request $request, $id)
    {
        /**
         * @var User $follower
         */
        $follower   = $this->getUser();
        /**
         * @var User $following
         */
        $following  = $this->getUserRepository()
            ->find($id);

        if (!$following) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $this->getUserManager()->follow($follower, $following);
        return $this->refresh($request);
    }

    /**
     * @Route ("/unfollow/{id}", name="unfollow", requirements={"id" = "\d+"})
     */
    public function unfollowAction(Request $request, $id)
    {
        /**
         * @var User $follower
         */
        $follower   = $this->getUser();
        /**
         * @var User $following
         */
        $following  = $this->getUserRepository()
            ->find($id);
        $this->getUserManager()->followRemove($follower, $following);
        return $this->refresh($request);
    }

    /**
     * @Route("/myMates", name="myMates")
     * @Template("ihateClientBundle:Content:myMates.html.twig")
     */
    public function myMatesAction(Request $request)
    {
        $user = $this->getUser();
        $follows = $user->getFollowers();
        return array(
            'user'      =>  $user,
            'follows'   =>  $follows
        );
    }
}
