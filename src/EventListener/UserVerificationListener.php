<?php

declare(strict_types=1);
 
namespace Amiltone\KeycloackTokenBundle\EventListener;
 
use Amiltone\KeycloackTokenBundle\Annotation\UserVerification as UserVerification;
use Amiltone\KeycloackTokenBundle\Service\DecodeTokenUser;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserVerificationListener
{
    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var DecodeTokenUser
     */
    private $decodeTokenUser;
 
    public function __construct(Reader $annotationReader, RequestStack $requestStack, DecodeTokenUser $decodeTokenUser)
    {
        $this->annotationReader = $annotationReader;
        $this->requestStack = $requestStack;
        $this->decodeTokenUser = $decodeTokenUser;
    }
 
    public function onKernelController(ControllerEvent  $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }
 
        $controllers = $event->getController();
        if (!is_array($controllers)) {
            return;
        }
 
        $this->handleAnnotation($controllers);
    }
 
    /**
     * @param iterable<mixed> $controllers
     * @throws RuntimeException
     */
    private function handleAnnotation(iterable $controllers): void
    {
        list($controller, $method) = $controllers;
 
        try {
            $controller = new ReflectionClass($controller);
        } catch (ReflectionException $e) {
            throw new RuntimeException('Failed to read annotation');
        }
        
        $classAnnotation = $this->annotationReader->getClassAnnotation($controller, UserVerification::class);
        $controllerAnnotation = $this->annotationReader->getMethodAnnotation($controller->getMethod($method), UserVerification::class);
        if ($classAnnotation instanceof UserVerification || $controllerAnnotation instanceof UserVerification) {
            $this->createUserData();
        }
    }

    /**
     * @throws BadRequestHttpException
     */
    private function createUserData(): void 
    {
        if(is_null($this->requestStack->getCurrentRequest())){
            throw new BadRequestHttpException("Request is null"); 
        }
        
        $this->requestStack->getCurrentRequest()->request->set(
            "user", $this->decodeTokenUser->getUser($this->requestStack->getCurrentRequest())
        );
    }
}