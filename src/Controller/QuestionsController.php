<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\QuestionsCategories;
use App\Entity\Users;
use App\Service\ConfigService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        $formBuilder = $this->createFormBuilder(null, [
            'action' => '/questions/new',
            'method' => 'POST',
        ]);
        if ($this->getUser()) {
            $formBuilder->add('user', HiddenType::class, [
            ]);
        } else {
            $formBuilder->add('email', EmailType::class, [
                'constraints' => [new NotBlank()],
                'label_attr' => [
                    //'class' => 'form-label',
                ],
                'row_attr' => [
                    'class' => 'mb-3',
                ],
                'attr' => [
                    'placeholder' => 'El. paštas',
                ],
            ]);
        }
        $formBuilder->add('questions_categories', EntityType::class, [
            'constraints' => [new NotBlank()],
            'label' => 'Kategorija',
            'label_attr' => [
                //'class' => 'form-label',
            ],
            'row_attr' => [
                'class' => 'mb-3',
            ],
            'class' => QuestionsCategories::class,
            'choice_label' => function (QuestionsCategories $category): string {
                return $category->title;
            }
            //'choice_lazy' => true,
           //'choice' => ChoiceList::fieldName($this, 'questions_categories'),
        ]);
        $formBuilder->add('question', TextareaType::class, [
            'constraints' => [new NotBlank()],
            'label' => 'Užduoti klausimą',
            'label_attr' => [
                //'class' => 'form-label',
            ],
            'row_attr' => [
                'class' => 'mb-3',
            ],
            'attr' => [
                'placeholder' => 'Užduoti klausimą',
            ],
        ]);
        $form = $formBuilder->getForm();


        //dv($form->getData());
        //dv($form->isSubmitted());
        $form->handleRequest($this->request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();

                /** @var Users|null $user */
                $user = $this->getUser();

                $question = new Questions();
                $question->question = $form->get('question')->getData();
                $question->email = $this->getUser() ? $user->email : $form->get('email')->getData();
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
