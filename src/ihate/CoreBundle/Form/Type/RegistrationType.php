<?php
namespace ihate\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new UserType());
        $builder->add(
            'terms',
            'checkbox',
            array('property_path' => 'termsAccepted')
        );
        $builder->add('Sign up for iHate', 'submit', array(
            'attr' => array('class' => 'btn btn-lg btn-primary btn-block')
        ));
    }

    public function getName()
    {
        return 'registration';
    }
}