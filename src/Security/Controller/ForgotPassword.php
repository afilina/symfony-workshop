<?php
declare(strict_types=1);

namespace App\Security\Controller;

use App\Email\Composer\EmailComposer;
use App\Email\Value\ResetPassword;
use App\Security\FormType\ForgotPasswordType;
use App\Security\Repository\UserRepository;
use App\Security\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

final class ForgotPassword
{
    private Twig $templating;
    private FormFactoryInterface $formFactory;
    private UserRepository $userRepository;
    private EmailComposer $emailComposer;

    public function __construct(
        Twig $templating,
        FormFactoryInterface $formFactory,
        UserRepository $userRepository,
        EmailComposer $emailComposer
    )
    {
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->userRepository = $userRepository;
        $this->emailComposer = $emailComposer;
    }

    public function handle(Request $request): Response
    {
        $form = $this->formFactory->create(ForgotPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $this->emailComposer->resetPassword(
                ResetPassword::create($user->getEmail())
            );
        }

        return new Response($this->templating->render(
            'security/forgot-password.html.twig', [
                'form' => $form->createView()
            ]
        ));
    }
}
