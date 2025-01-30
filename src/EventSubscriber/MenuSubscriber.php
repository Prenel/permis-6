<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class MenuSubscriber implements EventSubscriberInterface
{

    private Environment $twig;
    private RequestStack $requestStack; 
    
    public function __construct(Environment $twig, RequestStack $requestStack)
    {
        $this->twig = $twig;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
    
    public function onKernelController(ControllerEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $currentPath = $request->getPathInfo();

        $menuData =  [ 
            ['text' => 'Quizz', 'url' => "/quizz", 'active' => $currentPath === '/quizz'], 
            ['text' => 'Admin', 'url' => "/admin/category", 'active' => $currentPath === '/admin/category'], 
            ['text' => 'Profile', 'url' => "/profil", 'active' => $currentPath === '/profil'], 
        ];

        $subMenuData = []; 
        if (str_contains($currentPath, 'admin')){
            $subMenuData = [
                ['text' => 'Catégorie', 'url' => '/admin/category', 'active' => $currentPath === '/admin/category'], 
                ['text' => 'Question', 'url' => '/admin/question', 'active' => $currentPath === '/admin/question'], 
            ];
        }

        $this->twig->addGlobal('menuData', $menuData);
        $this->twig->addGlobal('subMenuData', $subMenuData);
        $this->twig->addGlobal('display', $currentPath === '/login' ? false : true);
    }

    
}
