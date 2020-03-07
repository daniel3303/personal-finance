<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-08-04
 * Time: 23:39
 */

namespace App\Contracts\CronJob\Model;


use DateInterval;

interface ExecutionContextInterface {
    /**
     * Returns the time elapsed since the last execution (in seconds)
     * Returns the current Unix Timestamp on the first execution
     *
     * @return DateInterval
     */
    public function getDeltaTime(): DateInterval;
}