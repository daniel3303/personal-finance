<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-08-04
 * Time: 20:24
 */

namespace App\Contracts\CronJob;


use App\Contracts\CronJob\Model\ExecutionContextInterface;
use DateInterval;
use Exception;

interface CronJobSubscriberInterface {
    /**
     * The minimum time in seconds between two distinct executions of this service.
     *
     * @return DateInterval
     */
    public static function getMinimumTimeBetweenExecutions() : DateInterval;

    /**
     * The code that will be executed by the service.
     * Should return an exception if the execution fails.
     *
     * @param ExecutionContextInterface $executionContext
     * @throws Exception
     */
    public function execute(ExecutionContextInterface $executionContext) : void;

}