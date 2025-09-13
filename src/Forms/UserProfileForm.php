<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('first_name', TextType::class, [
            'constraints' => [new NotBlank()],
            'label_attr' => [
                //'class' => 'form-label',
            ],
            'row_attr' => [
                'class' => 'mb-3',
            ],
            'attr' => [
                'placeholder' => 'Vardas',
            ],
            'required' => true,
        ]);
        $builder->add('last_name', TextType::class, [
            'constraints' => [new NotBlank()],
            'label_attr' => [
                //'class' => 'form-label',
            ],
            'row_attr' => [
                'class' => 'mb-3',
            ],
            'attr' => [
                'placeholder' => 'Pavardė',
            ],
            'required' => true,
        ]);
        $builder->add('email', EmailType::class, [
            'constraints' => [new NotBlank()],
            'label_attr' => [
                //'class' => 'form-label',
            ],
            'row_attr' => [
                'class' => 'mb-3',
            ],
            'attr' => [
                'placeholder' => 'El. paštas',
            ],
            'required' => true,
        ]);
        $builder->add('phone', TextType::class, [
            'constraints' => [new NotBlank()],
            'label_attr' => [
                //'class' => 'form-label',
            ],
            'row_attr' => [
                'class' => 'mb-3',
            ],
            'attr' => [
                'placeholder' => 'Telefonas',
            ],
            'required' => true,
        ]);
    }
}
