<?php
namespace ihate\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ihate\CoreBundle\Controller\AdvancedController;
use ihate\CoreBundle\Entity\User;
use ihate\CoreBundle\Entity\Post;
use ihate\CoreBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
class ClientController extends AdvancedController
{
    /**
     * @Route("/login", name="login")
     * @Template("ihateClientBundle:Client:frontPage.html.twig")
     */
    public function loginAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('ROLE_USER')) {
            $url = $this->generateUrl('inAccount');
            return $this->redirect($url);
        }
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error
        );
    }

    /**
     * @Route("/registration", name="registration")
     * @Template()
     */
    public function registrationAction(Request $request)
    {
        $form = $this->createForm(new RegistrationType(), new Registration());
        if($request->isMethod('POST')){
            $form->submit($request);
        }

        if($form->isValid()) {
            $data = $form->getData();
            $this->get('session')->getFlashBag()->set('registration', 'Your registration was successful!');
            $user = new User();

            $user->setName($data->getUser()->getName())
                ->setSurname($data->getUser()->getSurname())
                ->setEmail($data->getUser()->getEmail())
                ->setGender($data->getUser()->getGender())
                ->setCountry($data->getUser()->getCountry($user))
                ->setPassword($this->encodePassword($user, $data->getUser()->getPassword()));
            $this->em()->persist($user);
            $this->em()->flush();

            $url = $this->generateUrl('login');
            return $this->redirect($url);
        }
        return $this->render('ihateClientBundle:Client:registration.html.twig', array(
                'form' => $form->createView())
        );
    }

    private function encodePassword(User $user, $plainPassword)
    {
        /**
         * @var EncoderFactory $encoder
         */
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function checkAction()
    {
        // security layer
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        // security layer
    }

    /**
     * @Route ("/edit", name="edit")
     * @Template()
     */
    public function profileAction(Request $request)
    {
        $user   = $this->getUser();

        $entity = $this->getUserRepository()->find($user);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $type = new UserType(UserType::TYPE_EDIT);

        $form = $this->createForm($type, $entity);
        if ($request->getMethod() === 'POST')
        {
            $form->submit($request);

            if ($form->isValid()) {
                $this->get('session')
                        ->getFlashBag()
                        ->set('edit', 'Your profile has been updated!');
                $em = $this->em();
                $em->persist($entity);
                $em->flush($entity);
                $entity->upload();
                $em->flush($entity);
                return $this->redirect($this->generateUrl('edit'));
            }
        }
        return $this->render('ihateClientBundle:Client:edit.html.twig', array(
            'entity'    => $entity,
            'form'      => $form->createView(),
        ));
    }

    /**
     * @Route("/registration/terms", name="terms")
     * @Template("ihateClientBundle:Client:terms.html.twig")
     */
    public function termsAction()
    {
        return array();
    }
}