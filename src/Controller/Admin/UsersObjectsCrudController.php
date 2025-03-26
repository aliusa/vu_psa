<?php

namespace App\Controller\Admin;

use App\Entity\UsersObjects;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class UsersObjectsCrudController extends BaseCrudController
{
    public function __construct()
    {
        //
    }

    public static function getEntityFqcn(): string
    {
        return UsersObjects::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = Field::new('id');
            $fields[] = AssociationField::new('users', 'Klientas');
            $fields[] = Field::new('city', 'city');
            $fields[] = Field::new('street', 'street');
            $fields[] = Field::new('house', 'house');
            $fields[] = Field::new('flat', 'flat');
            $fields[] = Field::new('zip', 'zip');
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            $fields[] = AssociationField::new('users', 'Klientas')->autocomplete();
            $fields[] = AssociationField::new('country', 'country')->autocomplete()->setColumns('col-6 col-md-2');
            $fields[] = Field::new('city', 'city')->setColumns('col-6 col-md-2');
            $fields[] = Field::new('street', 'street')->setColumns('col-3 col-md-2');
            $fields[] = Field::new('house', 'house')->setColumns('col-3 col-md-2');
            $fields[] = Field::new('flat', 'flat')->setColumns('col-3 col-md-2');
            $fields[] = Field::new('zip', 'zip')->setColumns('col-3 col-md-2')->setFormTypeOption('attr', ['placeholder' => 12345]);

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = Field::new('id');
            $fields[] = AssociationField::new('users', 'Klientas');
            $fields[] = AssociationField::new('country', 'country');
            $fields[] = Field::new('city', 'city');
            $fields[] = Field::new('street', 'street');
            $fields[] = Field::new('house', 'house');
            $fields[] = Field::new('flat', 'flat');
            $fields[] = Field::new('zip', 'zip');
            $fields[] = AssociationField::new('admin', 'admin');
            $fields[] = DateTimeField::new('created_at', 'created_at');
            $fields[] = DateTimeField::new('updated_at', 'updated_at');
            /** @see UsersObjects::getUsersObjectsServicesBundles() */
            $fields[] = Field::new('UsersObjectsServicesBundles', 'Paslaugų paketai')->setTemplatePath('admin/users_objects/bundles_list.twig');

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
            ->add('users')
            ->add('country')
            ->add('city')
            ->add('street')
            ->add('house')
            ->add('flat')
            ->add('zip')
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
            ->setEntityLabelInSingular(function (?UsersObjects $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Klientų objektai',
                    Crud::PAGE_EDIT => "Klientų objektas #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Klientų objektas',
                };
            })
            ->setEntityLabelInPlural('Klientų objektai')
        ;

        return $crud;
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof UsersObjects) {
            return;
        }

        // Check for related entities
        if ($entityInstance->country) {
            $this->addFlash('danger', 'Negalima ištrinti įrašo, nes objektas turi priskirtą šalį.');
            return;
        }

        // Check for related entities
        if (!$entityInstance->users_objects_services_bundles->isEmpty()) {
            $this->addFlash('danger', 'Negalima ištrinti įrašo, nes objektas turi priskirtą paslaugų paketą.');
            return;
        }

        // Proceed with deletion
        parent::deleteEntity($entityManager, $entityInstance);
    }
}
