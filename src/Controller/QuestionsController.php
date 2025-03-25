<?php

namespace App\Controller;

use App\Entity\Payments;
use App\Entity\Questions;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuestionsController extends BaseController
{
    #[Route('/questions/new', methods: ['POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
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
                $this->managerRegistry->getManager()->persist($question);
                $this->managerRegistry->getManager()->flush();

                $this->addFlash("success", 'Klausimas užduotas');

                return $this->redirectToRoute('home_index');
            } else {
                return $this->redirectToRoute('home_index');
            }
        }

        return $this->render('questions/new.twig', [
            'questions_form' => $form->createView(),
        ]);
    }
}
