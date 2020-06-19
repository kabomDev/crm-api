<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     *@Route("/user/edit/{id}", name="user_edit") 
     */
    public function edit(User $user, Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $user->setPassword($encoder->encodePassword($user, $password));
            $em->flush();

            return $this->redirectToRoute('users');
        }

        return $this->render('user/edit.html.twig', [
            'editForm' => $form->createView()
        ]);
    }

    /**
     *@Route("/user/add", name="user_add") 
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        $user = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('users');
        }

        return $this->render('user/add.html.twig', [
            'addForm' => $form->createView()
        ]);
    }

    /**
     *@Route("/user/delete/{id} ", name="user_delete") 
     */
    public function delete(User $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute("users");
    }
}
