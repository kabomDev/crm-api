<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ClassicAuthenticator extends AbstractGuardAuthenticator
{
    protected UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function supports(Request $request)
    {
        return $request->getMethod() === 'POST' && $request->attributes->get('_route') === "security_login_classic";
    }

    public function getCredentials(Request $request)
    {
        $credentials = json_decode($request->getContent(), true);

        //si la requete est mal ecrite on fait une exception
        if (!$credentials) {
            throw new AuthenticationException("The json was not well formatted inside your request body");
        }

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //si ca ne correspond pas on se retrouve dans la fonction onAuthenticationFailure
        if (empty($credentials['username']) || empty($credentials['password'])) {
            throw new AuthenticationException("username or password not found in the request");
        }

        $user = $userProvider->loadUserByUsername($credentials['username']);
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //si le password est bon on se retrouve dans la fonction onAuthenticationSuccess
        return $this->encoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['message' => $exception->getMessage()], 400);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //si on arrive jusqu'ici on n'a pas besoin de mettre quelque chose
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        //nous renvois ce message si on est pas authentifié
        return new JsonResponse(["message" => "you must authenticate before accessing this route"], 403);
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
