<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-08-05
 * Time: 01:15
 */

namespace App\Component\CronJob\EventSubscriber;


use App\Contracts\CronJob\CronJobManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CronJobSubscriber implements EventSubscriberInterface {
    /**
     * @var CronJobManagerInterface
     */
    private CronJobManagerInterface $cronJobManager;

    public function __construct(CronJobManagerInterface $cronJobManager) {
        $this->cronJobManager = $cronJobManager;
    }

    public function beforeController(ControllerEvent $event): void {
        $this->cronJobManager->execute();
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents() : array {
        return [KernelEvents::CONTROLLER => 'beforeController'];
    }
}