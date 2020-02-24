<?php
namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;


class ControlUserSubscriber  implements EventSubscriberInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(RequestEvent $event)
    {   
        $method = $event->getRequest()->getMethod();
        $route = $event->getRequest()->server->get('REQUEST_URI');
        
       if(Request::METHOD_GET !==$method && '/api/users' ==$route){
        
            //Récupération du token
            $token = $this->tokenStorage->getToken();
        
            //Récupération de l'utilisateur connecté
            $userConnect = $token->getUser();
        
            //Récupération Role user à créer ou à modifier
            $request = $event->getRequest()->attributes->get('data');
            $userRole = $request->getRole()->getLibelle();
        
            // Control création admin system ou admin par un admin
            if (Request::METHOD_POST || Request::METHOD_PUT == $method && $userConnect->getRoles()[0] == "ROLE_ADMIN") {
            
                if ($userRole == "ADMIN_SYSTEM" || $userRole == "ADMIN" ) {
                    throw new HttpException( 401, 'Not privileged to request the resource.');    
                }
            }
            // Control création admin system ou admin
            if (Request::METHOD_POST || Request::METHOD_PUT == $method && $userConnect->getRoles()[0] == "ROLE_CAISSIER" || $userConnect->getRoles()[0] == "ROLE_PARTENAIRE") {
                if ($userRole == "ADMIN_SYSTEM" || $userRole == "ADMIN" 
                || $userRole == "CAISSIER" || $userRole == "PARTENAIRE") {
                    throw new HttpException( 401, 'Not privileged to request the resource.');    
                }
            }
        }
    

    }

    public static function getSubscribedEvents()
    {
        return array(
            'kernel.request' => 'onKernelRequest'
        );
    } 
}

   