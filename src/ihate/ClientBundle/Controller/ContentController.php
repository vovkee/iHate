<?php

namespace ihate\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ihate\CoreBundle\Controller\AdvancedController;
use Symfony\Component\Security\Core\SecurityContext;
use ihate\CoreBundle\Form\Type\PostType;
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
        $user = $this->get('security.context')->getToken()->getUser();
        $name = $user->getName();
        $postRepository = $this->getPostRepository();
        $post = $postRepository->showPost($user);
     //   $session = $request->getSession();
      //  $posts = $session->get('posts_history', array());
      //  $post = array()
        return array('name' => $name);
    }

    /**
     * @Route ("/search", name="search")
     * @Template("ihateClientBundle:Content:search.html.twig")
     */
    public function searchAction(Request $request)
    {
        $search = $this->get('request')->request->get('search');
        if($search != NULL){
            $userRepository = $this->getUserRepository();
            $result = $userRepository->search($search);
            return array(
                'search' => $search,
                'result' => $result
            );
        }
        else{
            return array(
                'search' => $search,
                'result' => ''
            );
        }
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
            $data = $form->getData();
            $post->setUser($this->getUser());
            $post->upload();
            $this->em()->persist($post);
            $this->em()->flush();

            $url = $this->generateUrl('inAccount');
            return $this->redirect($url);
        }
            return $this->render('ihateClientBundle:Content:create.html.twig', array(
                    'form' => $form->createView())
            );
    }
}
