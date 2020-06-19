<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customers", name="customers")
     */
    public function index(CustomerRepository $customerRepository)
    {
        $customers = $customerRepository->findAll();

        return $this->render('customer/index.html.twig', [
            'customers' => $customers
        ]);
    }

    /**
     *@Route("/customer/edit/{id}", name="customer_edit") 
     */
    public function edit(Customer $customer, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('customers');
        }

        return $this->render('customer/edit.html.twig', [
            'editForm' => $form->createView()
        ]);
    }

    /**
     *@Route("/customer/delete/{id} ", name="customer_delete") 
     */
    public function delete(Customer $customer, EntityManagerInterface $em)
    {
        $em->remove($customer);
        $em->flush();

        return $this->redirectToRoute("customers");
    }
}
