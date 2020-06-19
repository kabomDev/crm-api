<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(UserRepository $userRepository, InvoiceRepository $invoiceRepository, CustomerRepository $customerRepository)
    {
        $invoices = $invoiceRepository->count([]);
        $customers = $customerRepository->count([]);
        $users = $userRepository->count([]);

        return $this->render('dashboard/index.html.twig', [
            'invoices' => $invoices,
            'customers' => $customers,
            'users' => $users
        ]);
    }
}
