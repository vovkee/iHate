<?php
namespace ihate\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content','text')
            ->add('add', 'submit', array(
            'attr' => array('class' => 'btn btn-primary'),
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ihate\CoreBundle\Entity\Comment'
        ));
    }
    public function getName()
    {
        return 'comment';
    }
}