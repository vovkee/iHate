<?php
namespace ihate\Corebundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('email','email')
            ->add('gender', 'choice', array(
                    'choices' => array(
                        'm' => 'Male',
                        'f' => 'Female'
                ),
                'required'    => true,
                'empty_value' => 'Select your gender',
                'empty_data'  => null
            ))
            ->add('country')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'form-control')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ));
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ihate\CoreBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}