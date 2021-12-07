<?php

declare(strict_types=1);

namespace Amiltone\KeycloackTokenBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Amiltone\KeycloackTokenBundle\Service\DecodeTokenUser;

class RequestSubscriber implements EventSubscriberInterface
{
    /**
     * @var DecodeTokenUser
     */
    private $decodeTokenUser;

    public function __construct(DecodeTokenUser $decodeTokenUser)
    {
        $this->decodeTokenUser = $decodeTokenUser;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->attributes->get("userVerification")) {
           $request->request->set("user", $this->decodeTokenUser->getUser($request));
        }
    }

    
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest'
        ];
    }
}