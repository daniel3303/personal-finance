<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-08-04
 * Time: 20:32
 */

namespace App\Component\CronJob;


use App\Component\CronJob\Model\ExecutionContext;
use App\Contracts\CronJob\CronJobManagerInterface;
use App\Contracts\CronJob\CronJobSubscriberInterface;
use App\Entity\CronJob\CronJob;
use App\Repository\CronJob\CronJobRepository;
use ArrayObject;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CronJobManager implements CronJobManagerInterface {
    /**
     * @var ArrayObject|CronJobSubscriberInterface[]
     */
    private ArrayObject $cronJobs;
    /**
     * @var CronJobRepository
     */
    private CronJobRepository $cronJobRepository;
    /**
     * @var CacheInterface
     */
    private CacheInterface $cachePool;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var int
     */
    private int $cronMinExecutionSecs;

    public function __construct(CronJobRepository $cronJobRepository,
                                CacheInterface $cachePool, EntityManagerInterface $entityManager,
                                LoggerInterface $logger, int $cronMinExecutionSecs) {
        $this->cronJobs = new ArrayObject();
        $this->cronJobRepository = $cronJobRepository;
        $this->cachePool = $cachePool;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->cronMinExecutionSecs = $cronMinExecutionSecs;
    }

    public function registerCronJob(CronJobSubscriberInterface $cronJobSubscriber): void {
        $this->cronJobs->append($cronJobSubscriber);
    }

    /**
     * This manager can be executed using traditional unix cron jobs or through the application HTTP requests
     */
    public function execute(): void{
        try {
            $timestamp = $this->cachePool->get('cronJob.execution.last.timestamp', static function (ItemInterface $item) {
                $item->expiresAfter(null);
                return 0;
            });
            $lastExecution = Carbon::createFromTimestamp($timestamp);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Error while getting cache item "cronJob.execution.last.timestamp".');
            return;
        }

        // If the last execution was less than MIN_EXECUTION_INTERVAL seconds ago then abort.
        if($lastExecution->addSeconds($this->cronMinExecutionSecs)->isAfter(Carbon::now())){
            return;
        }


        // Executes all the jobs
        $now = Carbon::now();
        foreach ($this->cronJobs as $cronJob){
            $serviceId = get_class($cronJob);
            $lastCron = $this->cronJobRepository->findLastCronJobForService($serviceId);
            $lastExecutionDatetime = $lastCron ? $lastCron->getExecutionTime()->getTimestamp() : Carbon::createFromTimestamp(0);
            $deltaTime = $now->diffInSeconds($lastExecutionDatetime);
            $minExecutionInterval = CarbonInterval::instance($cronJob::getMinimumTimeBetweenExecutions());

            // Cron job was executed recently
            if($lastExecutionDatetime->add($minExecutionInterval)->isAfter($now)){
                return;
            }

            $context = new ExecutionContext($now->diff($lastExecutionDatetime));
            try {
                $cronJob->execute($context);
                $newCronJob = new CronJob($serviceId, $now, true);
            }catch (Exception $e){
                $newCronJob = new CronJob($serviceId, $now, true, $e->getMessage());
            }
            $this->entityManager->persist($newCronJob);
        }
        $this->entityManager->flush();

        // Updates last execution time
        try {
            $this->cachePool->delete('cronJob.execution.last.timestamp');
            $this->cachePool->get('cronJob.execution.last.timestamp', static function (ItemInterface $item) use ($now) {
                return $now->getTimestamp();
            });
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Error while getting/deleting cache item "cronJob.execution.last.timestamp".');
            return;
        }
    }
}