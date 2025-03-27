<?php

namespace App\Controller\Admin;

use App\Entity\Invoices;
use App\Entity\UsersObjectsServices;
use Doctrine\Common\Collections\ArrayCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class InvoicesCrudController extends BaseCrudController
{
    public function __construct()
    {
        //
    }

    public static function getEntityFqcn(): string
    {
        return Invoices::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = Field::new('id');
            /** @see Invoices::getUser() */
            $fields[] = Field::new('User', 'Klientas');
            /** @see Invoices::getUsersObjectsServicesBundles() */
            $fields[] = Field::new('UsersObjectsServicesBundles', 'users_objects_services_bundles');
            /** @see Invoices::getInvoiceTotal() */
            $fields[] = MoneyField::new('getInvoiceTotal', 'total')->setCurrency('EUR');
            /** @see Invoices::getPeriod() */
            $fields[] = Field::new('getPeriod', 'Periodas');
            $fields[] = DateField::new('due_date', 'due_date');
            $fields[] = DateTimeField::new('is_paid', 'is_paid');
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            ///** @see Invoices::getUser() */
            //$fields[] = AssociationField::new('User', 'Klientas');
            ///** @see Invoices::getUsersObjectsServicesBundles() */
            //$fields[] = AssociationField::new('UsersObjectsServicesBundles', 'users_objects_services_bundles');

            //$fields[] = TextField::new('is_paid', 'is_paid')->setColumns('col-4');
            //$fields[] = TextField::new('due_date', 'due_date')->setColumns('col-4');
            //$fields[] = BooleanField::new('is_paid', 'is_paid')->setColumns('col-4');

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = Field::new('id');
            /** @see Invoices::getUser() */
            $fields[] = Field::new('User', 'Klientas');
            /** @see Invoices::getUsersObjectsServicesBundles() */
            $fields[] = Field::new('UsersObjectsServicesBundles', 'users_objects_services_bundles');
            ///** @see Invoices::getInvoiceTotal() */
            //$fields[] = MoneyField::new('getInvoiceTotal', 'total')->setCurrency('EUR');
            $fields[] = DateTimeField::new('is_paid', 'is_paid');
            $fields[] = Field::new('series', 'Serija');
            /** @see Invoices::getNo() */
            $fields[] = Field::new('getNo', 'Numeris');
            /** @see Invoices::getPeriod() */
            $fields[] = Field::new('period', 'Periodas');
            $fields[] = DateField::new('due_date', 'due_date');
            $fields[] = DateTimeField::new('created_at', 'created_at');
            $fields[] = DateTimeField::new('updated_at', 'updated_at');
            /** @see Invoices::getInvoiceServices() */
            $fields[] = Field::new('getInvoiceServices', 'Priskirtos paslaugos')->setTemplatePath('admin/invoices/services_list.twig')
                ->formatValue(
                /** @var ArrayCollection|UsersObjectsServices[] $value */
                    static function ($value, Invoices $invoices) {
                        return [
                            'service' => $value,
                            'invoice' => $invoices,
                        ];
                    });

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
        $actions->getAsDto(Crud::PAGE_INDEX)->disableActions([Crud::PAGE_NEW, Crud::PAGE_EDIT, 'delete']);

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters = parent::configureFilters($filters);

        //https://symfony.com/bundles/EasyAdminBundle/current/filters.html

        $filters
            ->add('id')
            ///** @see Invoices::getInvoiceTotal() */
            //->add('getInvoiceTotal')
            ->add('due_date')
            //->add('is_paid')
            ->add('period_start')
            ->add('period_end')
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
            ->setEntityLabelInSingular(function (?Invoices $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Sąskaitą',
                    Crud::PAGE_EDIT => "Sąskaita #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Sąskaita',
                };
            })
            ->setEntityLabelInPlural('Sąskaitos')
        ;

        return $crud;
    }
}
