<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ApiKeyAuthenticator extends AbstractGuardAuthenticator
{

    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function supports(Request $request)
    {
        return $request->query->has('apiKey');
    }

    public function getCredentials(Request $request)
    {
        return $request->query->get('apiKey');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->repository->findOneBy(['apiKey' => $credentials]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        //si on a bien la clé api on renvoie true
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => "The provided api key is not valid !"
        ], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //si tout est bon on affiche les infos demandées
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(["message" => "you must provide an API key in the url query"], 403);
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
