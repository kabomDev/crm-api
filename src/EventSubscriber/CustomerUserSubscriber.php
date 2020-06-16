<?php

namespace App\EventSubscriber;

use App\Entity\Customer;
use Symfony\Component\Security\Core\Security;

class CustomerUserSubscriber //implements EventSubscriberInterface
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Customer $customer)
    {
        //si le customer n'a pas de user
        if (!$customer->getUser()) {
            //on se fait injecter le service Security pour recuperer l'utilisateur
            $customer->setUser($this->security->getUser());
        }
    }

    /**
     * Methode 1 mais pas vraiment a faire
     *
     */
    // public static function getSubscribedEvents()
    // {
    //     return [
    //         KernelEvents::VIEW => ['setUserOnCUstomer', EventPriorities::PRE_WRITE]
    //     ];
    // }

    // public function setUserOnCUstomer(ViewEvent $event)
    // {
    //     $entity = $event->getControllerResult();
    //     $request = $event->getRequest();

    //     if (!$entity instanceof Customer && $request->getMethod() !== 'POST') {
    //         return;
    //     }

    //     //on se fait injecter le service Security pour recuperer l'utilisateur
    //     $entity->setUser($this->security->getUser());
    // }


}
