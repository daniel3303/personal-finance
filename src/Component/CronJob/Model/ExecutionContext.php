<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-08-05
 * Time: 00:49
 */

namespace App\Component\CronJob\Model;


use App\Contracts\CronJob\Model\ExecutionContextInterface;
use Carbon\CarbonInterval;
use DateInterval;

class ExecutionContext implements ExecutionContextInterface {
    /**
     * @var CarbonInterval
     */
    private CarbonInterval $deltaTime;

    public function __construct(DateInterval $deltaTime) {
        $this->deltaTime = CarbonInterval::instance($deltaTime);
    }

    /**
     * @return CarbonInterval
     */
    public function getDeltaTime(): CarbonInterval {
        return $this->deltaTime;
    }

}