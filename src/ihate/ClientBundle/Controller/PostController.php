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

/**
 * @Route ("/")
 */
class PostController extends AdvancedController
{
    /**
     * @Route("/post/{id}", name="showOnePost")
     * @Template("ihateClientBundle:Content:showOnePost.html.twig")
     */
    public function getOnePostAction($id, Request $request)
    {
        $repository = $this->getPostRepository();
        $post = $repository->findOneById($id);
        $user = $this->getUser();
        if(!$post)
        {
            return $this->redirect($this->generateUrl('inAccount'));
        }
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);
        if($request->isMethod('POST')){
            $form->submit($request);
            if($form->isValid()){
                $data = $form->getData();

                $comment->setUser($user)
                        ->setContent($data->getContent())
                        ->setPost($post);
                $this->em()->persist($comment);
                $this->em()->flush();

                $comment = new Comment();
                $form = $this->createForm(new CommentType(), $comment);
            }
        }
        $commentRepository = $this->getCommentRepository();
        $comments = $commentRepository->findBy(array(
            'post' => $post->getId()
        ), array('createdAt' => 'DESC'));
        return array(
            'form'  =>  $form->createView(),
            'post'  =>  $post,
            'comments' => $comments,
            'user'  =>  $user
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

    /**
     * @Route("/myhates", name="myHates")
     * @Template("ihateClientBundle:Content:myHates.html.twig")
     */
    public function myHatesAction()
    {
        $user = $this->getUser();
        $name = $user->getName();
        $surname = $user->getSurname();
        $repository = $this->getPostRepository();
        $posts = $repository->getMyPosts($user);
        $path = $user->showImage();
        return array(
            'name'      =>  $name,
            'surname'   =>  $surname,
            'path'      =>  $path,
            'posts'     =>  $posts
        );
    }
}