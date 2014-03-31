<?php
namespace ihate\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ihate\CoreBundle\Controller\AdvancedController;
use ihate\CoreBundle\Entity\User;
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
     * @Route("/register", name="account_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new RegistrationType(), new Registration());
        if($request->isMethod('POST')){
            $form->submit($request);
        }

        if($form->isValid()) {
            $data = $form->getData();

            $user = new User();

            $user->setName($data->getUser()->getName())
                ->setSurname($data->getUser()->getSurname())
                ->setEmail($data->getUser()->getEmail())
                ->setGender($data->getUser()->getGender())
                ->setPassword($this->encodePassword($user, $data->getUser()->getPassword()));

            $this->em()->persist($user);
            $this->em()->flush();

            $url = $this->generateUrl('registration_confirm');
            return $this->redirect($url);
        }


        return $this->render('ihateClientBundle:Client:register.html.twig', array(
                'form' => $form->createView())
        );
    }

    /**
     * @Route("/register/confirm", name="registration_confirm")
     * @Template("ihateClientBundle:Client:regConfirm.html.twig")
     */
    public function regConfirmAction()
    {
        return array();
    }

    private function encodePassword(User $user, $plainPassword)
    {
        /**
         * @var EncoderFactory $encoder
         */
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user)
        ;

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
}