<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The passwords must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => false,
                    'mapped' => false,
                    'first_options' => ['label' => 'Password (optional)'],
                    'second_options' => ['label' => 'Repeat Password (optional)'],
                ]
            )
            ->add('email')
            ->add(
                'roles',
                CollectionType::class,
                [
                    'entry_type' => ChoiceType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_options' => [
                        'label' => false,
                        'choices' => User::$roletypes,
                    ],
                ]
            )
            ->add('enabled', CheckboxType::class, ['required' => false]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'AppBundle\Entity\User',
            ]
        );
    }
}