<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    public function supports(Request $request)
    {
        return true;
    }

    public function getCredentials(Request $request)
    {
        $credentials = $request->headers->get('Authorization');

        if (!$credentials) {
            throw new AuthenticationException("don't forget Authorization header");
        }

        return (int) str_replace("Bearer token-", "", $credentials);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //on retourne le user avec son token id
        return $userProvider->loadUserByUsername($credentials);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //si on arrive la c'est que tout est bon
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => 'bad token'
        ], 400);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'message' => 'you have be fully authenticated to view this route'
        ], 401);
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
