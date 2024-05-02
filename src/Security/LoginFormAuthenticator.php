<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    // Utilisation du trait pour gérer les redirections après l'authentification
    use TargetPathTrait;

    // Route vers laquelle rediriger pour se connecter
    public const LOGIN_ROUTE = 'app_login';

    // Constructeur prenant en paramètre l'interface de génération d'URL
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    // Méthode pour authentifier l'utilisateur
    public function authenticate(Request $request): Passport
    {
        // Récupération de l'e-mail de la requête
        $email = $request->request->get('email', '');
        
        // Stockage de l'e-mail dans la session
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        // Création d'un objet Passport avec les informations d'identification de l'utilisateur et les badges CSRF et RememberMe
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    // Méthode appelée après une authentification réussie
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Redirection vers la page précédente si elle existe, sinon redirection vers la page d'accueil en fonction du rôle de l'utilisateur
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        if (in_array('ROLE_SELLER', $token->getRoleNames())) {
            return new RedirectResponse($this->urlGenerator->generate('app_seller_index'));
        }
        if (in_array('ROLE_BUYER', $token->getRoleNames())) {
            return new RedirectResponse($this->urlGenerator->generate('app_user_buyer_index'));
        }
        return new RedirectResponse($this->urlGenerator->generate('app_index'));
    }

    // Méthode pour obtenir l'URL de connexion
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
