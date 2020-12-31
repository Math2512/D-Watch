<?php

namespace App\Event;

use App\Entity\User;
use App\Entity\Article;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE]
        ];
    }

    public function encodePassword(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($result instanceof User && $method === 'POST'){
            $hash = $this->encoder->encodePassword($result, $result->getPassword());
            $result->setPassword($hash);
            
        }

        if($result instanceof Article && $method === 'POST'){
            
            $JsonResponse = file_get_contents("https://unfurl.io/api/v2/preview?api_token=xC3jXNC2vXfiOt6bpZcq6cPs3ncJAZYxIX7YQfgGrXS9HbhJRVcARc0ZbTJP&url=".$result->getLink());
            $response = json_decode($JsonResponse, true);

            if(isset($response['open_graph']['images'][0]['url'])){
                $image = $response['open_graph']['images'][0]['url'];
                
                if($result->getTitle() == '')
                    isset($response['title']) ? $title = $response['title']: 'Titre';
                else
                    $title = $result->getTitle();

            }else{
                $image= null;
                $title = "Titre";
            }

            $result->setImgUrl($image);
            $result->setTitle($title);
            
        }
    }

}