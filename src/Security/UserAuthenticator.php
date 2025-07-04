<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

public function authenticate(Request $request): Passport
{
    $email = $request->get('email'); // Accessing the 'email' field from the request payload.

    $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

    return new Passport(
        new UserBadge($email),
        new PasswordCredentials($request->get('password')),
        [
            new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),
            new RememberMeBadge(),
        ]
    );
}

public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
{
    // Check if a target path exists (e.g., for redirections after accessing secured pages).
    if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        return new RedirectResponse($targetPath);
    }

    // Redirect based on the user's role.
    $roles = $token->getUser()->getRoles();
    if (in_array('ROLE_ADMIN', $roles, true)) {
        return new RedirectResponse($this->urlGenerator->generate('admin')); // Redirect admins to the dashboard.
    }else {
        return new RedirectResponse($this->urlGenerator->generate('clients_index')); // Redirect client to the profile.
    }

    // Redirect regular users to the homepage (or any other route).
    return new RedirectResponse($this->urlGenerator->generate('homepage'));
}

protected function getLoginUrl(Request $request): string
{
    return $this->urlGenerator->generate(self::LOGIN_ROUTE); // Redirect to the login page.
}
}
