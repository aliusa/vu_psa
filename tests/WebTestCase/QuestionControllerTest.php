<?php

namespace App\Tests\WebTestCase;

use App\Entity\Questions;
use App\Entity\QuestionsCategories;
use App\Repository\QuestionsRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuestionControllerTest extends WebTestCase
{
    public function testNewQuestionSubmissionNotPost(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        // Create a category to be used in the form
        $category = new QuestionsCategories();
        $category->title = 'Test Category for Functional Test';
        $entityManager->persist($category);
        $entityManager->flush();

        // The name of the form is derived from the form type class name,
        // by default it's the snake_cased version of the class name without the "Type" suffix.
        // So, QuestionsForm becomes "questions_form"
        $formData = [
            'questions_form' => [
                'email' => 'functional.test@example.com',
                'question' => 'This is a functional test question.',
                'questions_categories' => $category->getId(),
            ],
        ];


        $crawler = $client->request('GET', '/questions/new', $formData);

        // After successful submission, the controller redirects to 'my_questions'
        $this->assertResponseStatusCodeSame(405);
        //dump($crawler);
    }

    public function testNewQuestionSubmission(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        // Create a category to be used in the form
        $category = new QuestionsCategories();
        $category->title = 'Test Category for Functional Test';
        $entityManager->persist($category);
        $entityManager->flush();

        // The name of the form is derived from the form type class name,
        // by default it's the snake_cased version of the class name without the "Type" suffix.
        // So, QuestionsForm becomes "questions_form"
        $formData = [
            'questions_form' => [
                'email' => 'functional.test@example.com',
                'question' => 'This is a functional test question.',
                'questions_categories' => $category->getId(),
            ],
        ];


        //$crawler = $client->request('GET', '/');
        //$form = $crawler->selectButton('Pateikti')->form();
//
        //$form['questions_form[email]'] = 'functional.test@example.com';
        //$form['questions_form[phone]'] = '112';
        //$form['questions_form[question]'] = 'This is a functional test question.';
        //$form['questions_form[questions_categories]'] = $category->getId();

        //$crawler = $client->submit($form);


        $client->request('POST', '/questions/new', $formData);

        // After successful submission, the controller redirects to 'my_questions'
        $this->assertResponseRedirects('/users/my_questions');

        // Follow the redirect
        //$crawler = $client->followRedirect();

        // Check if the flash message is present
        //$this->assertSelectorTextContains('div.alert-success', 'Klausimas užduotas');

        // Check if the question was actually saved to the database
        /** @var QuestionsRepository $questionRepository */
        $questionRepository = static::getContainer()->get(QuestionsRepository::class);
        /** @var Questions|null $question */
        $question = $questionRepository->findOneBy(['email' => 'functional.test@example.com'], ['id' => 'DESC']);

        $this->assertNotNull($question);
        $this->assertSame('This is a functional test question.', $question->question);
        $this->assertSame($category->getId(), $question->questions_categories->getId());
    }

    public function testNewQuestionSubmissionInvalid(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        // Create a category to be used in the form
        $category = new QuestionsCategories();
        $category->title = 'Test Category for Functional Test';
        $entityManager->persist($category);
        $entityManager->flush();

        // The name of the form is derived from the form type class name,
        // by default it's the snake_cased version of the class name without the "Type" suffix.
        // So, QuestionsForm becomes "questions_form"
        $formData = [
            'questions_form' => [
                //'email' => 'functional.test.invalid@example.com',
                'question' => 'This is a functional test question.',
                'questions_categories' => $category->getId(),
            ],
        ];


        //$crawler = $client->request('GET', '/');
        //$form = $crawler->selectButton('Pateikti')->form();
//
        //$form['questions_form[email]'] = 'functional.test@example.com';
        //$form['questions_form[phone]'] = '112';
        //$form['questions_form[question]'] = 'This is a functional test question.';
        //$form['questions_form[questions_categories]'] = $category->getId();

        //$crawler = $client->submit($form);


        $client->request('POST', '/questions/new', $formData);

        // After successful submission, the controller redirects to 'my_questions'
        $this->assertResponseRedirects('/');

        // Follow the redirect
        //$crawler = $client->followRedirect();

        // Check if the flash message is present
        //$this->assertSelectorTextContains('div.alert-success', 'Klausimas užduotas');

        // Check if the question was actually saved to the database
        /** @var QuestionsRepository $questionRepository */
        $questionRepository = static::getContainer()->get(QuestionsRepository::class);
        /** @var Questions|null $question */
        $question = $questionRepository->findOneBy(['email' => 'functional.test.invalid@example.com'], ['id' => 'DESC']);

        $this->assertNull($question);
    }
}
