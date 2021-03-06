<?php
declare(strict_types=1);

namespace App\Email\Composer;

use App\Email\Value\ResetPassword;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment as Twig;

final class TwigEmailComposer implements EmailComposer
{
    private MailerInterface $mailer;
    private Twig $templating;
    private TranslatorInterface $translator;
    /**
     * Instead of injecting all the params, create a EmailComposerConfig object and register it as service,
     * giving it only the params that we need.
     */
    private ParameterBagInterface $params;

    public function __construct(
        MailerInterface $mailer,
        Twig $templating,
        TranslatorInterface $translator,
        ParameterBagInterface $params
    )
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->params = $params;
    }

    public function resetPassword(ResetPassword $resetPassword): void
    {
        $this->sendEmail(
            $this->params->get('email_sender_default'),
            $resetPassword->getEmail(),
            $this->translator->trans('Forgot Password', [], 'messages'),
            $this->templating->render('email/reset-password.txt.twig', ['message' => $resetPassword]),
            $this->templating->render('email/reset-password.html.twig', ['message' => $resetPassword])
        );
    }

    protected function sendEmail(string $from, string $recipient, string $subject, string $text, string $html): void
    {
        $email = (new Email())
            ->from($from)
            ->to($recipient)
            ->subject($subject)
            ->text($text)
            ->html($html);
        $this->mailer->send($email);
    }
}
