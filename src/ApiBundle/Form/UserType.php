<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                array(
                    'description' => 'Email.',
                    'constraints' => array(
                        new NotBlank(),
                        new Email(),
                        new Length(
                            array(
                                'min' => 2,
                                'max' => 255,
                                'minMessage' => 'email must be longer than 2 characters.',
                                'maxMessage' => 'email must be shorter than 255 characters.',
                            )
                        ),
                    ),
                )
            )
            ->add('plainPassword', PasswordType::class, array('description' => 'Password.'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => '\ApiBundle\Entity\User',
                'allow_extra_fields' => true,
                'cascade_validation' => true,
                'csrf_protection' => false
            )
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
