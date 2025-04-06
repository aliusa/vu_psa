<?php

namespace App\Controller\Admin;

use App\Entity\Structures;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class StructuresCrudController extends BaseCrudController
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getEntityFqcn(): string
    {
        return Structures::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];


        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = IdField::new('id');
            $fields[] = Field::new('title', 'title');
            $fields[] = Field::new('slug', 'slug')->setColumns('col-md-6');
            $fields[] = TextEditorField::new('description', 'description');
            $fields[] = BooleanField::new('visible', 'visible');
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            $fields[] = FormField::addColumn(8);
            $fields[] = Field::new('title', 'title')->setColumns('col-md-6');
            $fields[] = Field::new('slug', 'slug')->setColumns('col-md-6');

            $fields[] = FormField::addColumn(4);
            $fields[] = BooleanField::new('visible', 'visible')->setColumns('col-md-6');


            $fields[] = FormField::addColumn(12);
            $fields[] = Field::new('description')
                ->setFormType(CKEditorType::class)
                ->setFormTypeOptions([
                    'config'=>[
                        //'width' => '100%',
                        'height' => '600',
                        /** @see CKEditorConfiguration */
                        'toolbar' => 'full',//basic/standard/full
                        //'filebrowserUploadRoute' => 'post_ckeditor_image',//crash'ina
                        'filebrowserUploadRouteParameters' => ['slug' => 'image'],
                        'extraPlugins' => 'templates',
                        'rows' => '20',
                    ],
                    'attr' => ['rows' => '20'] ,
                ])
                ->setColumns('col-12');
            ;

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = FormField::addColumn(8);
            $fields[] = Field::new('title', 'title');
            $fields[] = Field::new('slug', 'slug');
            $fields[] = TextEditorField::new('description', 'description');
            $fields[] = BooleanField::new('visible', 'visible')->setColumns('col-md-6');

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
            ->add('description')
            ->add('created_at')
        ;

        return $filters;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = parent::configureCrud($crud);

        //Kad veiktų ckeditor redaktorius
        $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');

        $crud
            ->setDefaultSort(['id' => 'DESC'])
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular(function (?Structures $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Įrašą',
                    Crud::PAGE_EDIT => "Įrašas #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Įrašas',
                };
            })
            ->setEntityLabelInPlural('Įrašai')
        ;

        return $crud;
    }
}
