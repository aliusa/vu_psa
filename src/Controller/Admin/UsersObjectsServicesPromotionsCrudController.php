<?php

namespace App\Controller\Admin;

use App\Entity\UsersObjectsServicesPromotions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class UsersObjectsServicesPromotionsCrudController extends BaseCrudController
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getEntityFqcn(): string
    {
        return UsersObjectsServicesPromotions::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = IdField::new('id');
            //
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = FormField::addColumn(8);
            //

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
            ->setEntityLabelInSingular(function (?UsersObjectsServicesPromotions $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Paslaugų paketo paslaugos akciją',
                    Crud::PAGE_EDIT => "Paslaugų paketo paslaugos akcija #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Paslaugų paketo paslaugos akcijos',
                };
            })
            ->setEntityLabelInPlural('Paslaugų paketo paslaugos akcijos')
        ;

        return $crud;
    }
}
