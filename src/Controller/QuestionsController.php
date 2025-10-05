<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Users;
use App\Forms\QuestionsForm;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsController extends BaseController
{
    #[Route('/questions/new', methods: ['POST'])]
    public function new(
        EntityManagerInterface $entityManager,
        ConfigService $configService,
    ): Response
    {
        if (!$configService->getConfigValue(ConfigService::C_QUESTIONS_CAN_ASK)) {
            return new Response('');
        }
        $questions = new Questions();
        $form = $this->createForm(QuestionsForm::class, $questions, [
            'action' => '/questions/new',
            'method' => 'POST',
            'data' => [
                'user' => $this->getUser(),
            ],
        ]);

        $form->handleRequest($this->request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();

                /** @var Users|null $user */
                $user = $this->getUser();

                $question = new Questions();
                $question->question = $form->get('question')->getData();
                $question->email = $this->getUser() ? $user->email : $form->get('email')->getData();
                $question->phone = $this->getUser() ? $user->phone : $form->get('phone')->getData();
                $question->users = $this->getUser();
                $question->questions_categories = $form->get('questions_categories')->getData();
                $this->managerRegistry->getManager()->persist($question);
                $this->managerRegistry->getManager()->flush();

                $this->addFlash("success", 'Klausimas užduotas');


                if (!empty($_ENV['ADMIN_EMAIL'])) {
                    $this->mailerManager->sendMail('ADMIN_EMAIL', [
                        'subject' => 'Naujas klausimas',
                        'text' => <<<EOF
<p>Naujas klausimas:</p>
{$question->question}
EOF,
                    ]);
                }

                return $this->redirectToRoute('my_questions');
            } else {
                return $this->redirectToRoute('home_index');
            }
        }

        return $this->render('questions/new.twig', [
            'questions_form' => $form->createView(),
        ]);
    }
}
