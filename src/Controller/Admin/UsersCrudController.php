<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Entity\UsersObjects;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersCrudController extends BaseCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Users::class;
    }


    public function configureFields(string $pageName): iterable
    {
        /** @var Field[] $fields */
        $fields = [];

        if ($pageName === Crud::PAGE_INDEX) {
            //sąrašas

            $fields[] = Field::new('id');
            $fields[] = Field::new('first_name', 'first_name');
            $fields[] = Field::new('last_name', 'last_name');
            $fields[] = Field::new('email', 'email');
            //$fields[] = Field::new('phone', 'phone');
            //$fields[] = Field::new('ownedAmount');todo:uncomment
            $fields[] = DateTimeField::new('created_at', 'created_at');

        } elseif (in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW])) {
            //Redagavimas, kūrimas

            /*foreach ($fields as $key => $field) {
                if (in_array($field->getAsDto()->getProperty(), [])) {
                    $field->setFormTypeOption('disabled', 'disabled');//show, but disabled
                }
            }/**/

            $fields[] = EmailField::new('email', 'email')->setColumns('col-6');
            $fields[] = TelephoneField::new('phone', 'phone')->setColumns('col-6')->setFormTypeOption('attr', ['placeholder' => '+370....']);
            $fields[] = TextField::new('first_name', 'first_name')->setColumns('col-6');
            $fields[] = TextField::new('last_name', 'last_name')->setColumns('col-6');
            $fields[] = TextField::new('password', 'password')
                ->setFormTypeOptions(['mapped' => false])
                ->setFormType(PasswordType::class)
                ->setColumns('col-6')
            ;

        } elseif ($pageName === Crud::PAGE_DETAIL) {
            //Peržiūra

            $fields[] = Field::new('id');
            $fields[] = Field::new('first_name', 'first_name');
            $fields[] = Field::new('last_name', 'last_name');
            $fields[] = Field::new('email', 'email');
            $fields[] = Field::new('phone', 'phone');
            $fields[] = DateTimeField::new('created_at', 'created_at');
            $fields[] = DateTimeField::new('updated_at', 'updated_at');
            /** @see Users::getUsersOjectsList() */
            $fields[] = Field::new('UsersOjectsList', 'Objektai')->setTemplatePath('admin/users/objects_list.twig');
            /** @see Users::getQuestionsList() */
            $fields[] = Field::new('getQuestionsList', 'Klausimai')->setTemplatePath('admin/users/questions_list.twig');

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
            ->add('first_name')
            ->add('last_name')
            ->add('email')
            ->add('phone')
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
            ->setEntityLabelInSingular(function (?Users $entity, ?string $pageName) {
                return match ($pageName) {
                    Crud::PAGE_INDEX, Crud::PAGE_NEW => 'Klientą',
                    Crud::PAGE_EDIT => "Klientas #{$entity->getId()}",
                    Crud::PAGE_DETAIL => $entity->__tostring(),
                    default => 'Klientas',
                };
            })
            ->setEntityLabelInPlural('Klientai')
        ;

        return $crud;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }
    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }
    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }
    private function hashPassword() {
        return function(PostSubmitEvent $event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword(new Users(), $password);
            $form->getData()->setPassword($hash);
        };
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Users) {
            return;
        }

        // Check for related entities
        if (!$entityInstance->users_objects->isEmpty()) {
            $this->addFlash('danger', 'Negalima ištrinti įrašo, nes klientas turi priskirtus objektus.');
            return;
        }

        // Proceed with deletion
        parent::deleteEntity($entityManager, $entityInstance);
    }
}
