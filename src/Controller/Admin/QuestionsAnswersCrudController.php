<?php

namespace App\Controller\Admin;

use App\Entity\Questions;
use App\Entity\QuestionsAnswers;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class QuestionsAnswersCrudController extends BaseCrudController
{
    public function __construct(
        protected RequestStack $requestStack,
        protected ManagerRegistry $managerRegistry,
    )
    {
        parent::__construct();
    }

    public static function getEntityFqcn(): string
    {
        return QuestionsAnswers::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];


        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = IdField::new('id');
            $fields[] = AssociationField::new('questions', 'Klausimas');
            $fields[] = TextField::new('answer', 'Atsakymas');
            $fields[] = TextField::new('admin', 'admin');
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            $fields[] = AssociationField::new('questions', 'Klausimas')->autocomplete()->setFormTypeOption('required', true)->setFormTypeOption('disabled', 'disabled');
            $fields[] = Field::new('answer', 'Atsakymas');

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = FormField::addColumn(8);
            $fields[] = AssociationField::new('questions', 'Klausimas');
            $fields[] = Field::new('answer', 'Atsakymas');

            $fields[] = FormField::addColumn(4);
            $fields[] = IdField::new('id');
            $fields[] = AssociationField::new('admin', 'admin');
            $fields[] = DateTimeField::new('created_at', 'created_at');
            $fields[] = DateTimeField::new('updated_at', 'updated_at');
        }


        if (empty($fields)) {
            $fields = parent::configureFields($pageName);
        }

        return $fields;
    }


    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->remove(Crud::PAGE_INDEX, Action::EDIT);
        $actions->remove(Crud::PAGE_INDEX, Action::DELETE);

        $actions->remove(Crud::PAGE_DETAIL, Action::INDEX);
        $actions->remove(Crud::PAGE_DETAIL, Action::EDIT);
        $actions->remove(Crud::PAGE_DETAIL, Action::DELETE);

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters = parent::configureFilters($filters);

        //https://symfony.com/bundles/EasyAdminBundle/current/filters.html

        $filters
            ->add('id')
            ->add('questions')
            ->add('answer')
            ->add('admin')
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
            ->setEntityLabelInSingular(function (?QuestionsAnswers $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Klausimo atsakymą',
                    Crud::PAGE_EDIT => "Klausimo atsakymas #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Klausimo atsakymas',
                };
            })
            ->setEntityLabelInPlural('Klausimo atsakymai')
        ;

        return $crud;
    }

    public function createEntity(string $entityFqcn): QuestionsAnswers
    {
        /** @var QuestionsAnswers $questionsAnswers */
        $questionsAnswers = parent::createEntity($entityFqcn);
        $id = $this->requestStack->getCurrentRequest()->query->get('questions_id');
        $questionsAnswers->questions = $this->managerRegistry->getRepository(Questions::class)->find($id);
        return $questionsAnswers;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);

        /** @var QuestionsAnswers $entityInstance */

        /** @var AdminUrlGenerator $adminUrlGenerator */
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        $questionsAnswersCrudLink = $adminUrlGenerator
            ->setController(QuestionsCrudController::class)
            ->set('entityId', $entityInstance->questions->getId())
            ->setAction(Action::DETAIL)
            ->generateUrl();

        (new RedirectResponse($questionsAnswersCrudLink))->send();
    }
}
