<?php

namespace App\Controller\Admin;

use App\Entity\UsersObjectsServicesBundles;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class UsersObjectsServicesBundlesCrudController extends BaseCrudController
{
    public function __construct()
    {
        //
    }

    public static function getEntityFqcn(): string
    {
        return UsersObjectsServicesBundles::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = Field::new('id');
            $fields[] = AssociationField::new('users_object', 'Klientų objektas');
            $fields[] = Field::new('ServicesCount', 'Paslaugos');/** @see UsersObjectsServicesBundles::getServicesCount() */
            $fields[] = DateField::new('active_to', 'active_to');
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            $fields[] = AssociationField::new('users_object', 'Klientų objektas')->autocomplete();/** @see UsersObjectsServicesBundles::$users_object */
            $fields[] = DateField::new('active_to', 'active_to');

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = Field::new('id');
            $fields[] = AssociationField::new('users_object', 'Klientų objektas');
            $fields[] = DateField::new('active_to', 'active_to');
            $fields[] = AssociationField::new('admin', 'admin');
            $fields[] = DateTimeField::new('created_at', 'created_at');
            $fields[] = DateTimeField::new('updated_at', 'updated_at');
            /** @see UsersObjectsServicesBundles::getUsersObjectsServices() */
            $fields[] = Field::new('getUsersObjectsServices', 'Paslaugos')->setTemplatePath('admin/users_objects_bundles/services_list.twig');
            /** @see UsersObjectsServicesBundles::getInvoices() */
            $fields[] = Field::new('getInvoices', 'Sąskaitos')->setTemplatePath('admin/users_objects_bundles/invoices_list.twig');
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
            ->add('users_object')
            ->add('active_to')
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
            ->setEntityLabelInSingular(function (?UsersObjectsServicesBundles $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Klientų objektų paslaugų paketai',
                    Crud::PAGE_EDIT => "Klientų objektų paslaugų paketas #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Kliento objeto paslaugų paketas',
                };
            })
            ->setEntityLabelInPlural('Klientų objektų paslaugų paketai')
        ;

        return $crud;
    }
}
