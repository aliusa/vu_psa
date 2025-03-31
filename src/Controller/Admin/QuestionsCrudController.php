<?php

namespace App\Controller\Admin;

use App\Entity\Questions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class QuestionsCrudController extends BaseCrudController
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getEntityFqcn(): string
    {
        return Questions::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];


        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = IdField::new('id');
            $fields[] = AssociationField::new('users', 'Klientas');
            $fields[] = Field::new('email', 'email');
            $fields[] = Field::new('question', 'Klausimas');
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            //
        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = FormField::addColumn(8);
            $fields[] = AssociationField::new('users', 'Klientas');
            $fields[] = Field::new('email', 'email');
            $fields[] = Field::new('question', 'Klausimas');

            $fields[] = FormField::addColumn(4);
            $fields[] = IdField::new('id');
            $fields[] = DateTimeField::new('created_at', 'created_at');
            $fields[] = DateTimeField::new('updated_at', 'updated_at');

            $fields[] = FormField::addColumn(12);
            /** @see Questions::getQuestionsAnswers() */
            $fields[] = Field::new('getQuestionsAnswers', 'Atsakymai')->setTemplatePath('admin/questions/answers_list.twig');
        }


        if (empty($fields)) {
            $fields = parent::configureFields($pageName);
        }

        return $fields;

        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }


    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->remove(Crud::PAGE_INDEX, Action::EDIT);
        $actions->remove(Crud::PAGE_INDEX, Action::DELETE);
        $actions->remove(Crud::PAGE_DETAIL, Action::EDIT);
        $actions->remove(Crud::PAGE_DETAIL, Action::DELETE);

        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);


        /** @see static::answer() */
        $actions->add(Crud::PAGE_INDEX, Action::new('Atsakyti')
            ->linkToUrl(function (Questions $questions) {
                /** @var AdminUrlGenerator $adminUrlGenerator */
                $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

                $questionsAnswersCrudLink = $adminUrlGenerator
                    ->setController(QuestionsAnswersCrudController::class)
                    ->set('questions_id', $questions->getId())
                    ->setAction(Action::NEW)
                    ->generateUrl();
                return $questionsAnswersCrudLink;
            })
            ->setIcon('fa-solid fa-comment')
        );

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters = parent::configureFilters($filters);

        //https://symfony.com/bundles/EasyAdminBundle/current/filters.html

        $filters
            ->add('id')
            ->add('question')
            ->add('users')
            ->add('created_at')
        ;

        return $filters;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = parent::configureCrud($crud);

        $crud
            ->setDefaultSort(['id' => 'DESC'])
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular(function (?Questions $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Klausimą',
                    Crud::PAGE_EDIT => "Klausimas #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Klausimas',
                };
            })
            ->setEntityLabelInPlural('Klausimai')
        ;

        return $crud;
    }
}
