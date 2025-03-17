<?php

namespace App\Controller\Admin;

use App\Entity\UsersObjectsServices;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class UsersObjectsServicesCrudController extends BaseCrudController
{
    public function __construct()
    {
        //
    }

    public static function getEntityFqcn(): string
    {
        return UsersObjectsServices::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = Field::new('id');
            $fields[] = AssociationField::new('users_objects_services_bundles', 'users_objects_services_bundles');
            $fields[] = AssociationField::new('services', 'service');
            $fields[] = Field::new('amount', 'amount');
            $fields[] = MoneyField::new('unit_price', 'unit_price')->setCurrency('EUR');
            $fields[] = MoneyField::new('unit_adjustments', 'unit_adjustments')->setCurrency('EUR');
            $fields[] = MoneyField::new('unit_total', 'unit_total')->setCurrency('EUR');
            $fields[] = MoneyField::new('total_price', 'total_price')->setCurrency('EUR');
            $fields[] = DateField::new('active_to', 'active_to');
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            $fields[] = AssociationField::new('users_objects_services_bundles', 'users_objects_services_bundles')->autocomplete()->setColumns('col-12 col-md-6');/** @see UsersObjectsServices::$users_objects_services_bundles */
            $fields[] = AssociationField::new('services', 'services')->autocomplete()->setColumns('col-12 col-md-6');/** @see UsersObjectsServices::$services */
            $fields[] = Field::new('amount', 'amount')->setColumns('col-4');
            $fields[] = MoneyField::new('unit_price', 'unit_price')->setCurrency('EUR')->setColumns('col-4')->setFormTypeOption('attr', ['placeholder' => '00.00']);
            $fields[] = MoneyField::new('unit_adjustments', 'unit_adjustments')->setCurrency('EUR')->setColumns('col-4')->setFormTypeOption('attr', ['placeholder' => '00.00']);
            $fields[] = MoneyField::new('total_price', 'total_price')->setCurrency('EUR')->setColumns('col-4')->setDisabled(true)->setFormTypeOption('attr', ['placeholder' => '00.00']);
            $fields[] = DateField::new('active_to', 'active_to');

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = Field::new('id');
            $fields[] = AssociationField::new('users_objects_services_bundles', 'users_objects_services_bundles');
            $fields[] = AssociationField::new('services', 'service');
            $fields[] = Field::new('amount', 'amount');
            $fields[] = MoneyField::new('unit_price', 'unit_price')->setCurrency('EUR');
            $fields[] = MoneyField::new('unit_adjustments', 'unit_adjustments')->setCurrency('EUR');
            $fields[] = MoneyField::new('unit_total', 'unit_total')->setCurrency('EUR');
            $fields[] = MoneyField::new('total_price', 'total_price')->setCurrency('EUR');
            $fields[] = DateField::new('active_to', 'active_to');
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
            ->add('users_objects_services_bundles')
            ->add('services')
            ->add('amount')
            ->add('unit_price')
            ->add('total_price')
            ->add('active_to')
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
            ->setEntityLabelInSingular(function (?UsersObjectsServices $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Klientų objektų paslaugų paketų paslaugą',
                    Crud::PAGE_EDIT => "Klientų objektų paslaugų paketo paslauga #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Kliento objeto paslaugų paketo paslaugos',
                };
            })
            ->setEntityLabelInPlural('Klientų objektų paslaugų paketų paslaugos')
        ;

        return $crud;
    }
}
