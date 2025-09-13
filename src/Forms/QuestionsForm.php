<?php

namespace App\Forms;

use App\Entity\QuestionsCategories;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuestionsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        /** @var Users|null $user */
        $user = $options['data']['user'] ?? null;
        if ($user) {
            $builder->add('user', HiddenType::class, [
            ]);
        } else {
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
            ]);
        }
        $builder->add('questions_categories', EntityType::class, [
            'constraints' => [new NotBlank()],
            'label' => 'Kategorija',
            'label_attr' => [
                //'class' => 'form-label',
            ],
            'row_attr' => [
                'class' => 'mb-3',
            ],
            'class' => QuestionsCategories::class,
            'choice_label' => function (QuestionsCategories $category): string {
                return $category->title;
            }
            //'choice_lazy' => true,
            //'choice' => ChoiceList::fieldName($this, 'questions_categories'),
        ]);
        $builder->add('question', TextareaType::class, [
            'constraints' => [new NotBlank()],
            'label' => 'Užduoti klausimą',
            'label_attr' => [
                //'class' => 'form-label',
            ],
            'row_attr' => [
                'class' => 'mb-3',
            ],
            'attr' => [
                'placeholder' => 'Užduoti klausimą',
            ],
        ]);
    }
}
