<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-07-16
 * Time: 02:44
 */

namespace App\CronSubscriber;

use App\Contracts\CronJob\CronJobSubscriberInterface;
use App\Contracts\CronJob\Model\ExecutionContextInterface;
use App\Repository\Transaction\Recurrent\RecurrentTransactionRepository;
use Carbon\CarbonInterval;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class RecurrentTransactionSubscriber implements CronJobSubscriberInterface {
    /**
     * @var RecurrentTransactionRepository
     */
    private RecurrentTransactionRepository $recurrentTransactionRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(RecurrentTransactionRepository $recurrentTransactionRepository, EntityManagerInterface $entityManager) {
        $this->recurrentTransactionRepository = $recurrentTransactionRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function execute(ExecutionContextInterface $executionContext): void {
        $recurrentTransactions = $this->recurrentTransactionRepository->findRequiringUpdate();

        foreach ($recurrentTransactions as $recurrentTransaction){
            $recurrentTransaction->createTransactions();
        }
        $this->entityManager->flush();

    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public static function getMinimumTimeBetweenExecutions(): CarbonInterval {
        return CarbonInterval::create(0,0,0,0,0,0,10);
    }
}
