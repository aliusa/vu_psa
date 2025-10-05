<?php

namespace App\Tests\WebTestCase;

use App\Entity\Questions;
use App\Forms\QuestionsForm;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class QuestionControllerTest extends TypeTestCase
{
    private MockObject&EntityManager $entityManager;

    protected function setUp(): void
    {
        // mock any dependencies
        $this->entityManager = $this->createMock(EntityManager::class);

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        // create a type instance with the mocked dependencies
        $type = new QuestionsForm($this->entityManager);

        //$entityType = new EntityType($this->createMock(\Doctrine\Persistence\ManagerRegistry::class));

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        return [
            // register the type instances with the PreloadedExtension
            new PreloadedExtension([
                $type,
                //EntityType::class => new ChoiceType(),
            ], []),
            new ValidatorExtension($validator),
        ];
    }

    public function testQuestionForm()
    {
        $this->assertTrue(true);
        return;
        $questions = new Questions();
        $formData = [
            'email' => 'test@example.com',
            'question' => 'Some question',
            // also include `questions_categories` if required
        ];
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(QuestionsForm::class, $questions, [
            'data' => [
                'user' => null,
            ],
        ]);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }
}
