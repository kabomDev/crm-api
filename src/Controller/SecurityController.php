<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     *@Route("/login", name="admin_security_login") 
     */
    public function login(FormFactoryInterface $factory, AuthenticationUtils $utils)
    {
        dump($utils->getLastAuthenticationError());

        $form = $factory->createNamed('', LoginType::class);

        return $this->render('security/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *@Route("/logout" , name="admin_security_logout") 
     */
    public function logout()
    {
    }

    /**
     * @Route("/api/login_token", name="security_login_token", methods={"POST"})
     */
    public function token()
    {
    }
}
