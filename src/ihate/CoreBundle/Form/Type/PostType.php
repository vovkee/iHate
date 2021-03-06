<?php
namespace ihate\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', 'textarea')
            ->add('embed', 'text', array('label'    =>  'Youtube video'))
            ->add('file')
            ->add('Ok', 'submit', array(
            'attr' => array('class' => 'btn btn-primary')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ihate\CoreBundle\Entity\Post'
        ));
    }
    public function getName()
    {
        return 'post';
    }
}