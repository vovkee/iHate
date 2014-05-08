<?php
namespace ihate\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ihate\CoreBundle\Controller\AdvancedController;
use ihate\CoreBundle\Entity\User;
use ihate\CoreBundle\Entity\Post;
use ihate\CoreBundle\Entity\Hate;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ihate\CoreBundle\Form\Type\RegistrationType;
use ihate\CoreBundle\Form\Model\Registration;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * @Route ("/")
 */
class HateController extends AdvancedController
{
    /**
     * @Route("/hate/{id}", name="hate",requirements={"id" = "\d+"})
     * @Template()
     */
    public  function hateAction(Request $request, $id)
    {
        $user = $this->getUser();
        $hate = new Hate();
        $hate->setUser($this->getUser());
        $hate->setPost($this->getPostRepository()->findOneById($id)->addHate($hate));
        $this->em()->persist($hate);
        $this->em()->flush();
        if (!$hate) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $this->getUserManager()->hate($user, $hate);
        return $this->refresh($request);
    }

    /**
     * @Route ("/uNhate/{id}", name="unHate", requirements={"id" = "\d+"})
     */
    public function unHateAction(Request $request, $id)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $post = $this->getPostRepository()->find($id);
        /**
         * @var Hate $hate
         */
        $hate = $this->getHateRepository()->findOneBy(array(
            'user' => $user,
            'post' => $post
        ));
        if ($hate) {
            $post->removeHate($hate);
            $this->getUserManager()->hateRemove($user, $hate);
        }
        return $this->refresh($request);
    }
}