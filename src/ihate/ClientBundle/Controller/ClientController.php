<?php
namespace ihate\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ihate\CoreBundle\Controller\AdvancedController;
use ihate\CoreBundle\Entity\User;
use ihate\CoreBundle\Entity\Post;
use ihate\CoreBundle\Form\Type\UserType;
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
            $data = $form->
                getData();

            $user = new User();

            $user->setName($data->getUser()->getName())
                ->setSurname($data->getUser()->getSurname())
                ->setEmail($data->getUser()->getEmail())
                ->setGender($data->getUser()->getGender())
                ->setCountry($data->getUser()->getCountry($user))
                ->setPassword($this->encodePassword($user, $data->getUser()->getPassword()));

            $this->em()->persist($user);
            $this->em()->flush();

            $url = $this->generateUrl('registration_confirm');
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

    /**
     * @Route ("/edit", name="edit")
     * @Method("POST")
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
                $this->getAllFormErrors($form);
            }
        }
        return $this->render('ihateClientBundle:Client:edit.html.twig', array(
            'entity'    => $entity,
            'form'      => $form->createView(),
        ));
    }

    /**
     * Returns array of form errors
     * @param \Symfony\Component\Form\Form $form
     * @return array
     */
    protected function getAllFormErrors(\Symfony\Component\Form\Form $form) {
        $errors = array();
        $translation = $this->container->get('translator');

        foreach ($form->getErrors() as $key => $error) {
            $template = $translation->trans($error->getMessageTemplate(), array(), "validators");
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getAllFormErrors($child);
                }
            }
        }
        return $errors;
    }
    /**
     * @Route("/register/confirm", name="registration_confirm")
     * @Template("ihateClientBundle:Client:regConfirm.html.twig")
     */
    public function regConfirmAction()
    {
        return array();
    }
}