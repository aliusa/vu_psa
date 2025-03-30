<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Field\CKEditorField;
use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ServicesCrudController extends BaseCrudController
{
    public function __construct(
        private TranslatorInterface $translator,
        private AdminUrlGenerator $adminUrlGenerator,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Services::class;
    }


    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = IdField::new('id');
            $fields[] = Field::new('title', 'title');
            $fields[] = MoneyField::new('price', 'price')->setCurrency('EUR');
            $fields[] = DateField::new('active_from', 'active_from');
            $fields[] = DateField::new('active_to', 'active_to');
            $fields[] = Field::new('advertise', 'Reklamuoti');
            $fields[] = AssociationField::new('services_categories', 'Kategorija');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            $fields[] = Field::new('title', 'title')->setColumns('col-6');
            $fields[] = MoneyField::new('price', 'price')->setCurrency('EUR')->setColumns('col-6')->setFormTypeOption('attr', ['placeholder' => '00.00']);
            $fields[] = CKEditorField::new('description', 'description')
                ->setFormTypeOptions([
                    'config' => [
                        'toolbar' => 'basic',//basic/standard/full
                    ],
                ])
            ;
            /** @see Services::$services_categories */
            $fields[] = AssociationField::new('services_categories', 'Kategorija')
                ->autocomplete()
                ->setColumns('col-12 col-md-6')
                ->setFormTypeOption('required', true);
            $fields[] = DateField::new('active_from', 'active_from')->setColumns('col-6');
            $fields[] = DateField::new('active_to', 'active_to')->setColumns('col-6');
            $fields[] = Field::new('advertise', 'Reklamuoti');

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            //$fields = parent::configureFields($pageName);

            $fields[] = FormField::addColumn(8);
            $fields[] = Field::new('title', 'title');
            $fields[] = MoneyField::new('price', 'price')->setCurrency('EUR');
            $fields[] = Field::new('description', 'description');
            $fields[] = Field::new('advertise', 'Reklamuoti');
            $fields[] = AssociationField::new('services_categories', 'Kategorija');
            $fields[] = DateField::new('active_from', 'active_from');
            $fields[] = DateField::new('active_to', 'active_to');

            $fields[] = FormField::addColumn(4);
            $fields[] = IdField::new('id');
            $fields[] = AssociationField::new('admin', 'admin');
            $fields[] = DateTimeField::new('created_at', 'created_at');
            $fields[] = DateTimeField::new('updated_at', 'updated_at');

        }

        if(empty($fields)){
            $fields = parent::configureFields($pageName);
        }

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);

        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters = parent::configureFilters($filters);

        //https://symfony.com/bundles/EasyAdminBundle/current/filters.html

        $filters
            ->add('id')
            ->add('title')
            ->add('price')
            ->add('active_from')
            ->add('active_to')
            ->add('advertise')
            ->add('services_categories')
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
            ->setEntityLabelInSingular(function (?Services $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Paslaugą',
                    Crud::PAGE_EDIT => "Paslauga #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Paslauga',
                };
            })
            ->setEntityLabelInPlural('Paslaugos')
        ;

        return $crud;
    }
}
