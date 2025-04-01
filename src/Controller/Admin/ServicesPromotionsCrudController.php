<?php

namespace App\Controller\Admin;

use App\Entity\ServicesPromotions;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;

class ServicesPromotionsCrudController extends BaseCrudController
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getEntityFqcn(): string
    {
        return ServicesPromotions::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = IdField::new('id');
            $fields[] = AssociationField::new('services', 'service');
            $fields[] = PercentField::new('discount', 'discount')
                ->setNumDecimals(2)
                //->formatValue(function ($value) {
                //    return $value . ' %';
                //})
            ;
            $fields[] = IntegerField::new('months', 'months');
            $fields[] = DateField::new('active_from', 'active_from');
            $fields[] = DateField::new('active_to', 'active_to');
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            $fields[] = AssociationField::new('services', 'service')->autocomplete()->setFormTypeOption('required', true)->setColumns('col-md-4');
            $fields[] = PercentField::new('discount', 'discount')
                ->setNumDecimals(2)
                ->setHelp('Įveskite nuolaidą procentais (%) nuo 0 iki 100.')
                ->setColumns('col-md-4')
                ->setFormTypeOption('attr', ['placeholder' => '00.00'])
            ;
            $fields[] = NumberField::new('months', 'months')->setHelp('Kiek mėnesių taikyti nuolaidą. 1-12')->setColumns('col-md-4');
            $fields[] = DateField::new('active_from', 'active_from')->setColumns('col-md-4')->setHelp('Imtinai');
            $fields[] = DateField::new('active_to', 'active_to')->setColumns('col-md-4')->setHelp('Imtinai');

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = FormField::addColumn(8);
            $fields[] = AssociationField::new('services', 'service');
            $fields[] = PercentField::new('discount', 'discount')
                ->setNumDecimals(2)
                //->formatValue(function ($value) {
                //    return $value . ' %';
                //})
            ;
            $fields[] = IntegerField::new('months', 'months')->setHelp('Kiek mėnesių taikyti nuolaidą. 1-12.');
            $fields[] = DateField::new('active_from', 'active_from')->setHelp('Imtinai');
            $fields[] = DateField::new('active_to', 'active_to')->setHelp('Imtinai');

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
            ->add('services')
            ->add('discount')
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
            ->setEntityLabelInSingular(function (?ServicesPromotions $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Akciją',
                    Crud::PAGE_EDIT => "Akcija #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Akcijos',
                };
            })
            ->setEntityLabelInPlural('Akcijos')
        ;

        return $crud;
    }
}
