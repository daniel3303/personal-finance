<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-28
 * Time: 03:28
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Looks for changes in the following meta-parameters and stores changes in the user's session
 * - per-page
 * Class MetaParameterSubscriber
 * @package App\EventSubscriber
 */
class MetaParameterSubscriber implements EventSubscriberInterface {
    public function __construct() {
    }

    public function onKernelController(ControllerEvent $event){
        $request = $event->getRequest();
        if($request->query->has('per-page')){
            $perPage = max($request->query->getInt('per-page', 0), 10);
            $request->getSession()->set('per-page', $perPage);
        }

    }

    public static function getSubscribedEvents() {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

}