<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

abstract class BaseCrudController extends AbstractCrudController
{
    public function __construct()
    {
        //parent::__construct();
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = parent::configureFields($pageName);

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            //Hide fields in listing
            $fields = array_filter($fields, static function(Field $item) {
                return !in_array($item->getAsDto()->getProperty(), ['updated_at']);
            });

            foreach ($fields as $field) {
                //disable toggleable fields in listing
                if (in_array($field->getAsDto()->getProperty(), ['confirmed', 'enabled', 'invalid', 'marketing_accepted', 'via_email', 'via_push'])) {
                    $field->setFormTypeOption('disabled', 'disabled');//show, but disabled
                }
            }

        } elseif ($pageName === Crud::PAGE_EDIT) {
            //Redagavimas

            foreach ($fields as $key => $field) {
                if (in_array($field->getAsDto()->getProperty(), ['created_at', 'updated_at'])) {
                    //$field->setFormTypeOption('disabled', 'disabled');//show, but disabled
                    $field->hideOnForm();//hide
                }
            }

        } elseif ($pageName === Crud::PAGE_NEW) {
            //Kūrimas

            foreach ($fields as $key => $field) {
                if (in_array($field->getAsDto()->getProperty(), ['created_at', 'updated_at'])) {
                    $field->hideWhenCreating();
                }
            }

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            //

        }


        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = parent::configureCrud($crud);

        $crud
            //Kad visam TVS atvaizduotų tokiu formatu.
            ->setDateTimeFormat('Y-MM-dd HH:mm:ss')
            // the max number of entities to display per page
            ->setPaginatorPageSize(30)
        ;

        return $crud;
    }
    public function configureAssets(Assets $assets): Assets
    {
        $assets = parent::configureAssets($assets);

        $assets->addJsFile('/public/admin/js/jquery.min.js');

        //$assets->addJsFile('/public/xdan-datetimepicker/js.js');
        //$assets->addCssFile('/public/xdan-datetimepicker/css.css');



        $assets->addCssFile('/public/vendor/flatpickr/flatpickr.min.css');
        $assets->addJsFile('/public/vendor/flatpickr/flatpickr.js');
        $assets->addJsFile('/public/vendor/flatpickr/lt.js');

        $assets->addJsFile('/public/admin/js/scripts.js');
        $assets->addCssFile('/public/admin/css/admin.css');

        return $assets;
    }
}
