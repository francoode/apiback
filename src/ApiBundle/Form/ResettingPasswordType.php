<?php
namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResettingPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('description' => 'Username'))
            ->add(
                'url',
                null,
                array('mapped' => false, 'description' => 'URL to execute the password reset')
            )
            ->add('password', null, array('description' => 'Password'))
            ->add('confirmation_token', null, array('description' => 'Token generated'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'allow_extra_fields' => true,
            )
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
