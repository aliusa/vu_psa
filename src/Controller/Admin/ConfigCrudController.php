<?php

namespace App\Controller\Admin;

use App\Entity\Config;
use App\Service\ConfigService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConfigCrudController extends BaseCrudController
{
    public function __construct(
        private ConfigService $configService,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Config::class;
    }

    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = IdField::new('id');
            $fields[] = Field::new('key');
            $fields[] = TextField::new('description')
                ->setValue('description')
                ->formatValue(function ($value, Config $config) {
                    if ($params = $this->configService->getConfigParams($config->key)) {
                        return $params['description'] ?? null;
                    }
                    return $config->key;
                });
            $fields[] = TextField::new('default_value')
                ->setValue('default_value')
                ->formatValue(function ($value, Config $config) {
                    if ($params = $this->configService->getConfigParams($config->key)) {
                        return $this->configService->formatValue($params['type'], $params['default_value'] ?? null);
                    }
                    return null;
                });
            $fields[] = TextField::new('value')->setValue('value')
                ->formatValue(function ($value, Config $config) {
                    if ($params = $this->configService->getConfigParams($config->key)) {
                        return $this->configService->formatValue($params['type'], $config->value);
                    }
                    return strval($config->value);
                });

        } elseif ($pageName === Crud::PAGE_EDIT) {
            //Redagavimas

            $fields[] = TextField::new('key')
                ->setDisabled()->setColumns(6);

            $instance = $this->getContext()->getEntity()->getInstance();
            if ($instance instanceof Config) {
                if ($params = $this->configService->getConfigParams($instance->key)) {
                    $fields[] = $this->configService->addField($params['type'] ?? null, 'value')->setColumns(6);
                } else {
                    $fields[] = TextField::new('value')->setColumns(6);
                }

            }


        } elseif ($pageName === Crud::PAGE_NEW) {
            //Kūrimas

            if ($this->configService->hasNotSetChoices()) {
                $fields[] = ChoiceField::new('key')->setChoices(
                    $this->configService->getNotSetChoices()
                );
            }


        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = FormField::addColumn(8);
            $fields[] = Field::new('key');
            $fields[] = Field::new('value');
            $fields[] = TextField::new('default_value')
                ->setValue('default_value')
                ->formatValue(function ($value, Config $config) {
                    if ($params = $this->configService->getConfigParams($config->key)) {
                        return $this->configService->formatValue($params['type'], $params['default_value'] ?? null);
                    }
                    return null;
                });

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

        if (!$this->configService->hasNotSetChoices()) {
            $actions->getAsDto(Crud::PAGE_INDEX)->disableActions([Action::NEW]);
        }

        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters = parent::configureFilters($filters);

        $filters
            ->add('key');

        return $filters;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = parent::configureCrud($crud);

        $crud
            ->setDefaultSort(['key' => 'ASC'])
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular(function (?Config $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Nustatymas',
                    Crud::PAGE_EDIT => "Nustatymą #{$entity->getId()}",
                    //Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Nustatymas',
                };
            })
            ->setEntityLabelInPlural('Nustatymai');

        return $crud;
    }
}
