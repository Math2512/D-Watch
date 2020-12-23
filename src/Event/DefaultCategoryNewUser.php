<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DefaultCategoryNewUser implements EventSubscriberInterface {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['addCategoryDefault', EventPriorities::POST_WRITE]
        ];
    }

    public function addCategoryDefault(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($result instanceof User && $method === 'POST'){
            $category = new Category;
            $category->setNom('Autres'); 
            $category->setUser($result); 
            
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            
        }
    }
}