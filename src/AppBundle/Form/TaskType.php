<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
            ->add('description')
            ->add('startDate',DateTimeType::class, array(
        'widget' => 'single_text'))
            ->add('endDate',DateTimeType::class, array(
                'widget' => 'single_text'))
            ->add('isRecurrent')
            ->add('parent')
            ->add('createAt',DateTimeType::class, array(
                'widget' => 'single_text'))
            ->add('userOwner')
            ->add('study')
            ->add('userAssigned')
            ->add('business')
            ->add('company')->add('contact')
            ->add('state')
            ->add('recurrence');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task',
            'csrf_protection' => false

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_task';
    }


}
