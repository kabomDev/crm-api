<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login_classic", name="security_login_classic", methods={"POST"})
     */
    public function login_classic()
    {
        $user = $this->getUser();

        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'message' => 'authentication was successful'
        ]);
    }

    /**
     * @Route("/api/login_token", name="security_login_token", methods={"POST"})
     */
    public function token()
    {
        $user = $this->getUser();

        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'message' => 'authentication was successful',
            'token' => 'token-' . $user->getId()
        ]);
    }
}
