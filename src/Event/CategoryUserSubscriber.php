<?php

namespace App\Event;

use App\Entity\Category;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CategoryUserSubscriber implements EventSubscriberInterface{
   
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setUserCategory', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setUserCategory(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($result instanceof Category && $method === 'POST'){
            $result->setUser($this->security->getUser());
              
        }
    }
}