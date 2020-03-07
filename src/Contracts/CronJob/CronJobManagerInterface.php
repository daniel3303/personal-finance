<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-08-04
 * Time: 20:24
 */

namespace App\Contracts\CronJob;


interface CronJobManagerInterface {
    public function registerCronJob(CronJobSubscriberInterface $cronJobSubscriber): void;

    public function execute(): void;

}