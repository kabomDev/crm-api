<?php

namespace App\Controller;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;

class IncrementController
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(Invoice $data)
    {
        $chrono = $data->getChrono();
        $chrono++;
        $data->setChrono($chrono);

        $this->em->flush();

        return $data;
    }
}
