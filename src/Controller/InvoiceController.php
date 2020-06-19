<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    /**
     * @Route("/invoice", name="invoices_index")
     */
    public function index(InvoiceRepository $invoiceRepository)
    {
        $invoices = $invoiceRepository->findAll();

        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoices,
        ]);
    }

    /**
     *@Route("/invoice/edit/{id}", name="invoice_edit") 
     */
    public function edit(Invoice $invoice, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('invoices_index');
        }

        return $this->render('invoice/edit.html.twig', [
            'editForm' => $form->createView()
        ]);
    }

    /**
     *@Route("/invoices/delete/{id} ", name="invoice_delete") 
     */
    public function delete(Invoice $invoice, EntityManagerInterface $em)
    {
        $em->remove($invoice);
        $em->flush();

        return $this->redirectToRoute("invoices_index");
    }
}
