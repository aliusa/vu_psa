<?php

namespace App\Tests\WebTestCase;

use App\Entity\Questions;
use App\Forms\QuestionsForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class QuestionControllerTest extends TypeTestCase
{
    private EntityManager $entityManager;

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

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        return [
            // register the type instances with the PreloadedExtension
            new PreloadedExtension([$type], []),
            new ValidatorExtension($validator),
        ];
    }

    public function testQuestionForm()
    {
        $questions = new Questions();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(QuestionsForm::class, null);

        $formData = [
            'email' => 'test@example.com',
            'question' => 'Some question',
            // also include `questions_categories` if required
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }
}
