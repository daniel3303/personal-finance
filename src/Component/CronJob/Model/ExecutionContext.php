<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-08-05
 * Time: 00:49
 */

namespace App\Component\CronJob\Model;


use App\Contracts\CronJob\Model\ExecutionContextInterface;

class ExecutionContext implements ExecutionContextInterface {
    /**
     * @var int
     */
    private int $deltaTime;

    public function __construct(int $deltaTime) {
        $this->deltaTime = $deltaTime;
    }

    /**
     * @return int
     */
    public function getDeltaTime(): int {
        return $this->deltaTime;
    }

}