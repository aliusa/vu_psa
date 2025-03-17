<?php

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class MailerManager
{
    protected TranslatorInterface $translator;

    protected MailerInterface $mailer;

    protected ContainerInterface $container;
    private EntityManagerInterface $entityManager;
    private Environment $twigEnvironment;


    public function __construct(
        ContainerInterface $container,
        TranslatorInterface $translator,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager,
        Environment $twigEnvironment,
    )
    {
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->twigEnvironment = $twigEnvironment;
    }

    //region Users
    public function user_confirm(Tokens $tokens)
    {
        $params['subject'] = 'Prašome patvirtinti savo registraciją';
        $params['html'] = "Patvirtinkite savo registraciją, paspausdami šią nuorodą<br/><a href='{$tokens->getConfirmUrl()}'>{$tokens->getConfirmUrl()}</a>";
        $params['text'] = strip_tags($params['html']);
        $this->sendMail([$tokens->user->email], $params);
    }
    //endregion Users



    //region Messages
    public function user_sendMessages(Message $message)
    {
        $email = $message->user->email;
        if(!empty($email)){
            $params['subject'] = $message->message_group->subject;
            $params['html'] = $message->message_group->message;
            $params['text'] = $message->message_group->description;
            $this->sendMail([$email], $params);
        }
    }


    //region send
    /**
     * Siunčiama el.laiškai administroriams.
     *
     * @param array $params
     * @return void
     */
    protected function sendMailAdmin(array $params)
    {
        $adminEmails = explode(',', $_ENV['MAIL_ADMIN']);
        $this->sendMail($adminEmails, $params);
    }
    public function sendMail(array $recipients, array $params)
    {
        if(!empty($recipients)){
            $params['receivers'] = implode(',', $recipients);
        }

        /** @see \Twig\Environment::render() */
        $html = $this->twigEnvironment->render('email.twig', $params);


        $email = (new Email())
            ->from(new Address($_ENV['MAIL_FROM']))
            //->addTo()
            //->cc()
            //->bcc()
            //->replyTo()
            //->priority()
            ->subject($params['subject'])
            ->text($params['text'])//plain text (rodo, jei nerodo html)
            ->html($html)
            ;

        foreach ($recipients as $recipient) {
            $email->addTo(new Address($recipient));
        }

        $this->mailer->send($email);
    }

    /**
     * @param array|Users[] $recipients
     * @param array $params
     * @param string $user_group
     * @return void
     */
    protected function sendMessage(array $recipients, array $params, string $user_group='SYSTEM'){
        if (empty($recipients)) {
            return;
        }
        foreach ($recipients as $recipient){
            $this->createSingleGroupMessage(
                $recipient,
                $params['subject'] ?? '',
                $params['text'] ?? null,
                $params['html'] ?? null,
                $user_group,
                true
            );
        }
    }


    public static function getBaseUrl(): string
    {
        return Request::createFromGlobals()->getScheme() . '://' . Request::createFromGlobals()->getHttpHost();
    }

    protected function getTemplate(string $content = '', string $receiver = ''): ?string
    {
        $file = null;
        if (file_exists('../templates/email.html')) {
            //tvs
            $file = file_get_contents('../templates/email.html');
        } elseif (file_exists('templates/email.html')) {
            //cli
            $file = file_get_contents('templates/email.html');
        }

        if ($file) {
            return str_replace(['CONTENT', 'RECEIVER'], [$content, $receiver], $file);
        }

        return null;
    }
    //endregion send
}
