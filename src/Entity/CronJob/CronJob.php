<?php

namespace App\Entity\CronJob;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CronJob\CronJobRepository")
 */
class CronJob {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private string $service;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $executionTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $success;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    private ?string $output;

    /**
     * CronJob constructor.
     * @param string $service
     * @param DateTime $executionTime
     * @param bool $success
     * @param string|null $output
     */
    public function __construct(string $service, DateTime $executionTime, bool $success, ?string $output = null) {
        $this->service = $service;
        $this->executionTime = $executionTime;
        $this->success = $success;
        $this->output = $output;
    }


    public function getId(): ?int {
        return $this->id;
    }

    public function getService(): string {
        return $this->service;
    }

    public function getExecutionTime(): Carbon {
        return Carbon::instance($this->executionTime);
    }

    public function getSuccess(): bool {
        return $this->success;
    }

    public function getOutput(): ?string {
        return $this->output;
    }


}
