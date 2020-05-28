<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordTokenAuthenticator extends AbstractGuardAuthenticator
{
    protected UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function supports(Request $request)
    {
        return $request->getMethod() === 'POST' && $request->attributes->get('_route') === "security_login_token";
    }

    public function getCredentials(Request $request)
    {
        //on decode le json
        $credentials = json_decode($request->getContent(), true);

        if (!$credentials) {
            throw new AuthenticationException("Bad formatted JSON");
        }

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (empty($credentials['username']) || empty($credentials['password'])) {
            throw new AuthenticationException("Username or password not found in the request body");
        }

        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //si le password est bon on se retrouve dans la fonction onAuthenticationSuccess
        if ($this->encoder->isPasswordValid($user, $credentials['password']) === false) {
            throw new AuthenticationException("bad password");
        }

        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        dd('fail');
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
