<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * @see https://symfony.com/doc/current/security/custom_authenticator.html
 */
class CustomAuthenticator extends AbstractAuthenticator
{
    public const LOGIN_ROUTE = 'app_login';
     use TargetPathTrait;

     public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
       return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        // Récupère l'email/username depuis le formulaire de connexion
       // Le champ doit s'appeler "_username" dans le formulaire HTML
       // Si le champ n'existe pas, retourne une chaîne vide par défaut
         $email = $request->request->get('_username', '');
         $password = $request->request->get('_password', '');

         // Sauvegarde le dernier email/username saisi en session
        // Cela permet de ré-afficher l'email dans le formulaire en cas d'échec de connexion
        // LAST_USERNAME est une constante Symfony pour stocker cette information
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);
        // Crée et retourne un objet Passport qui contient toutes les informations d'authentification
        return new Passport(
            // UserBadge : Identifiant de l'utilisateur (email, username, etc.)
           // Symfony utilisera cet identifiant pour charger l'utilisateur depuis la base de données
           // via le UserProvider configuré dans security.yaml
            new UserBadge($email),
            // PasswordCredentials : Contient le mot de passe en clair saisi par l'utilisateur
            // Symfony comparera automatiquement ce mot de passe avec le hash stocké en base
             // en utilisant le password_hasher configuré
            new PasswordCredentials($password),
            // Tableau de "badges" optionnels qui ajoutent des fonctionnalités supplémentaires

            [
             // CsrfTokenBadge : Protection contre les attaques CSRF (Cross-Site Request Forgery)
            // Vérifie que le token CSRF envoyé correspond à celui généré par Symfony
            // 'authenticate' est l'identifiant du token (doit correspondre au formulaire)
            // Si le token est invalide, l'authentification échoue
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Redirection vers la page précédente si elle existe
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        // Message flash de succès (optionnel)
          $request->getSession()->set('_flash.success', 'Connexion réussie !');
        // Redirection selon le rôle
         $user = $token->getUser();
        // Redirection par défaut
         return new RedirectResponse($this->urlGenerator->generate("app_departement_list"));
        
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    // public function start(Request $request, ?AuthenticationException $authException = null): Response
    // {
    //     /*
    //      * If you would like this class to control what happens when an anonymous user accesses a
    //      * protected page (e.g. redirect to /login), uncomment this method and make this class
    //      * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
    //      *
    //      * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
    //      */
    // }
}
