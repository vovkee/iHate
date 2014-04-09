<?php

namespace ihate\ClientBundle\Controller;

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
     * @Route("/", name="inAccount")
     * @Template("ihateClientBundle:Client:inAccount.html.twig")
     */
    public function homeAction(Request $request)
    {
        $user = $this->getUser();
        $name = $user->getName();
        $surname = $user->getSurname();
        $repository = $this->getPostRepository();
        $posts = $repository->showPost($user);
        $path = $user->showImage();
     //   var_dump($path);
        return array(
            'name'      =>  $name,
            'surname'   =>  $surname,
            'path'      =>  $path,
            'posts'     =>  $posts
        );
    }

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
                'users' => ''
            );
        }
    }

    /**
     * @Route ("/follow/{id}", name="follow")
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

        $this->getUserManager()->connect($follower, $following);
        return $this->refresh($request);
    }
    /**
     * @Route ("/unfollow/{id}", name="unfollow")
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
     * @Route ("/create", name="create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(new PostType(), $post);
        if($request->isMethod('POST')){
            $form->submit($request);
        }
        if($form->isValid()) {
            $post->setUser($this->getUser());
            $this->em()->persist($post);
            $this->em()->flush();
            $post->upload();
            $this->em()->flush();

            $url = $this->generateUrl('inAccount');
            return $this->redirect($url);
        }
            return $this->render('ihateClientBundle:Content:create.html.twig', array(
                    'form' => $form->createView())
            );
    }

    /**
     * @Route ("/edit", name="edit")
     * @Template()
     */
    public function profileAction()
    {
        $user   = $this->getUser();
        $entity = $this->getUserRepository()->find($user);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $type = new UserType(UserType::TYPE_EDIT);

        $form = $this->createForm($type, $entity);

        $request = $this->get('request_stack')->getCurrentRequest();
        if ($request->getMethod() === 'POST')
        {
            $form->submit($request);

            if ($form->isValid()) {
                $this->em()->persist($entity);
                $this->em()->flush();
                $entity->upload();
                $this->em()->flush();

                return $this->redirect($this->generateUrl('edit'));
            } else {
                var_dump($form->getErrors());
            }
        }
        return $this->render('ihateClientBundle:Client:edit.html.twig', array(
            'entity'    => $entity,
            'form'      => $form->createView(),
        ));
    }

    private function refresh(Request $request)
    {
        $url = $request->server->get('HTTP_REFERER', false);
        return $this->redirect($url?$url:$this->generateUrl('inAccount'));
    }
}
