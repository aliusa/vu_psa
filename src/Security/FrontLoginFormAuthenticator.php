<?php

namespace App\Security;

use App\Controller\SecurityFrontController;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FrontLoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private bool $debug = false;

    public function __construct(
        private HttpUtils $httpUtils,
        private HttpKernelInterface $httpKernel,
        private UserProviderInterface $userProvider,
        private EntityManagerInterface $entityManager,
    )
    {
        /** @see \Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator */
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        if ($request->attributes->get('_route') === 'app_login') {
            $subRequest = $this->httpUtils->createRequest($request, 'app_login');
            $response = $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
            if (200 === $response->getStatusCode()) {
                $response->setStatusCode(401);
            }

            return $response;
        } else {
            //redirect from /app to /app/login

            $url = $this->getLoginUrl($request);

            return new RedirectResponse($url);
        }
    }

    public function supports(Request $request): bool
    {
        return
            in_array($request->attributes->get('_route'), ['app_login'])
            && $request->isMethod('POST');
    }

    protected function getLoginUrl(Request $request): string
    {
        /** @see SecurityFrontController::login() */
        return $this->httpUtils->generateUri($request, 'app_login');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');
        $csrfToken = $request->request->get('_csrf_token', '');

        //todo 2025-01-13 13:26 alius: fix symfony7 php84 upgrade

        $em = $this->entityManager;

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        $userBadge = new UserBadge($email, static function (string $email) use ($em) {
            $user = $em->getRepository(Users::class)->findOneBy(['email' => $email]);

            if (!$user) {
                throw new UserNotFoundException('Email could not be found.');
            }

            return $user;
        });

        $passport = new Passport(
            $userBadge,
            new PasswordCredentials($password),
            [
                new RememberMeBadge(),
                new CsrfTokenBadge('authenticate', $csrfToken),
            ],
        );
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var Users $user */
        $user = $token->getUser();
        $request->getSession()->set('currentObject', $user->users_objects->first());
        return $this->httpUtils->createRedirectResponse($request, '/');
    }
}
