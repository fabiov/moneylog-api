<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Interfaces\RelatedUserInterface;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;;

use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class RelatedUserSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setOwner', EventPriorities::PRE_WRITE],
        ];
    }

    public function setOwner(ViewEvent $event)
    {
        $method        = $event->getRequest()->getMethod();
        $relatedObject = $event->getControllerResult();

        if ($relatedObject instanceof RelatedUserInterface && Request::METHOD_POST === $method) {
            $token = $this->tokenStorage->getToken();
            if ($token) {
                $owner = $token->getUser();
                if ($owner instanceof User) {
                    $relatedObject->setUser($owner); // Attach the user to the not yet persisted Article
                }
            }
        }
    }
}
