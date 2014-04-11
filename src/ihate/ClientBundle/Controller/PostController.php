<?php

namespace ihate\ClientBundle\Controller;

use ihate\CoreBundle\Entity\Comment;
use ihate\CoreBundle\Form\Type\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ihate\CoreBundle\Controller\AdvancedController;
use Symfony\Component\Security\Core\SecurityContext;
use ihate\CoreBundle\Form\Type\PostType;
use ihate\CoreBundle\Form\Type\UserType;
use ihate\CoreBundle\Entity\User;
use ihate\CoreBundle\Entity\Post;
use ihate\CoreBundle\Entity\Country;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route ("/")
 */
class PostController extends AdvancedController
{
    /**
     * @Route("/post/{id}", name="showPost")
     * @Template("ihateClientBundle:Content:showPost.html.twig")
     */
    public function showPost($id, Request $request)
    {
        $repository = $this->getPostRepository();
        $post = $repository->findOneById($id);
        $user = $this->getUser();
        var_dump($user);
        if(!$post)
        {
            return $this->redirect($this->generateUrl('inAccount'));
        }
        $commentRepository = $this->getCommentRepository();
        $comments = $commentRepository->findBy(array(
            //'post' => $post,
            'post' => $post->getId()
        ));// @todo: order by created_at

        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);
        if($request->isMethod('POST')){
            $form->submit($request);
            if($form->isValid()){
                $data = $form->getData();
                $comment->setUser($data->getUser($user))
                        ->setText($data->getText())
                        ->;

            }
        }
        return array(
            'form'  =>  $form->createView(),
            'post'  =>  $post,
            'comments' => $comments
        );
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
}