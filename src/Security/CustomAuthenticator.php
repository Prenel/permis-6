<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class CustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): bool
    {
        // $supports = $request->getPathInfo() === '/login' && $request->isMethod('POST');
        // error_log('CustomAuthenticator supports() called. Path: ' . $request->getPathInfo() . ', Method: ' . $request->getMethod() . ', Supports: ' . ($supports ? 'true' : 'false'));
        return $request->getPathInfo() === "/login_check" && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true);
        error_log('CustomAuthenticator authenticate() called. Data: ' . json_encode($data));
        if (!isset($data['username'], $data['password'], $data['_csrf_token'])) {
            error_log('CustomAuthenticator authenticate(): Missing fields in request data.');
            throw new \InvalidArgumentException('Invalid JSON data or missing fields.');
        }

        $username = $data['username'];
        $password = $data['password'];

        error_log('CustomAuthenticator authenticate(): Username: ' . $username);

        $request->getSession()->set(Security::LAST_USERNAME, $username);


        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $data['_csrf_token']),
            ]
        );


    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        error_log('CustomAuthenticator onAuthenticationSuccess() called.');

        // if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        //     error_log('CustomAuthenticator onAuthenticationSuccess(): Redirecting to target path: ' . $targetPath);
        //     return new RedirectResponse($targetPath);
        // }

        $redirectUrl = $this->urlGenerator->generate('app_question');
        error_log('CustomAuthenticator onAuthenticationSuccess(): Redirecting to default: ' . $redirectUrl);
        
        return new JsonResponse([
            'success' => true,
            'redirectUrl' => $this->urlGenerator->generate('app_question'),
        ], 200);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // if ($request->hasSession()) {
        //     $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        // }
        error_log('CustomAuthenticator onAuthenticationFailure() called. Error: ' . $exception->getMessageKey());
        return new JsonResponse([
            'success' => false,
            'error' => $exception->getMessageKey(),
            'redirectUrl' => $this->getLoginUrl($request),
        ], 401);
    }

    protected function getLoginUrl(Request $request): string
    {
        $loginUrl = $this->urlGenerator->generate(self::LOGIN_ROUTE);
        error_log('CustomAuthenticator getLoginUrl(): ' . $loginUrl);

        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}

