<?php

namespace App\Controller\Admin\Field;

use App\Core\Form\Type\LeafletType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

class LeafletField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null)
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            // use this method if your field needs a specific form theme to render properly
            ->addFormTheme('admin/fields/leaflet.edit.twig')

            // this defines the Twig template used to render this field in 'index' and 'detail' pages
            // (this is not used in the 'edit'/'new' pages because they use Symfony Forms themes)
            ->setTemplatePath('admin/fields/leaflet.detail.twig')
            ->addJsFiles('public/vendor/leaflet/dist/leaflet.js')
            ->addCssFiles('public/vendor/leaflet/dist/leaflet.css')
            ->addJsFiles('public/admin/js/leaflet.js')
            ->setFormType(LeafletType::class)
            ;
    }
}
