<?php

declare(strict_types=1);

namespace Amiltone\KeycloackTokenBundle\Tests\EventSubscriber;

use PHPUnit\Util\Exception;
use App\EventListener\RequestSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class RequestSubscriberTest extends TestCase
{
    public function testSubscriber()
    {
        /** @var \App\Service\DecodeTokenUser&\PHPUnit\Framework\MockObject\MockObject $decodeTokenUser */
        $decodeTokenUser = $this->getMockBuilder(\App\Service\DecodeTokenUser::class)->getMock();
        /** @var Symfony\Component\HttpKernel\HttpKernelInterface&\PHPUnit\Framework\MockObject\MockObject $kernel */
        $kernel  = $this->getMockBuilder(KernelInterface::class)->getMock();

        $subscriber = new RequestSubscriber($decodeTokenUser);
        
        $event = new ExceptionEvent($kernel, new Request([], [], ["userVerification" => true]), 1, new Exception());
        
        $decodeTokenUser->expects($this->once())->method('getUser');
        $subscriber->onKernelRequest($event);
    }
}