<?php

namespace App\Doctrine\Listener;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;

class InvoiceChronoListener
{
    protected InvoiceRepository $repository;

    public function __construct(InvoiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function Prepersist(Invoice $invoice)
    {
        $lastChrono = $this->repository->findLastChrono();
        $invoice->setChrono($lastChrono + 1);
    }
}
