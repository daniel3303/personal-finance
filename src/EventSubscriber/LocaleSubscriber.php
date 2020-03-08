<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-07-16
 * Time: 02:44
 */

namespace App\EventSubscriber;

use App\Entity\User\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class LocaleSubscriber implements EventSubscriberInterface {
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function afterSecurity(RequestEvent $event) :void {
        $request = $event->getRequest();
        if($this->security->getUser() !== null && $request->getSession()->get('_locale', null) === null){
            /**
             * @var User $user
             */
            $user = $this->security->getUser();
            $request->getSession()->set('_locale', $user->getLocale());
        }
    }

    public function onKernelRequest(RequestEvent $event) :void {
        $request = $event->getRequest();

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale') ?? $request->query->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
            $request->setLocale($locale);
            return;
        }

        // use the session locale
        if($request->getSession()->get('_locale', null) !== null){
            $request->setLocale($request->getSession()->get('_locale'));
        }
    }


    public static function getSubscribedEvents() : array {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20], ['afterSecurity', -20]],
        ];
    }

}
