<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-05-26
 * Time: 17:54
 */

namespace App\Twig;


use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use DateInterval;
use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DateExtension extends AbstractExtension {

    /**
     * @var string
     */
    private $locale;

    public function __construct(string $defaultLocale) {
        $this->locale = $defaultLocale;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFunctions() {
        return array(
            new TwigFunction('datePeriod', array($this, 'datePeriodFilter')),
            new TwigFunction('intervalToText', array($this, 'dateIntervalToText')),
            new TwigFilter('dateDiffForHumans', array($this, 'dateDiffForHumans')),
        );
    }

    public function getFilters() {
        return array(
            new TwigFilter('intervalToText', array($this, 'dateIntervalToText')),
            new TwigFilter('dateDiffForHumans', array($this, 'dateDiffForHumans')),
            new TwigFilter('carbon', array($this, 'carbon')),
        );
    }

    /**
     * Creates a DatePeriod object.
     * It allows iteration over a set of dates and times, recurring at regular intervals, over a given period.
     *
     * @param DateTime $start The start date of the period
     * @param DateTime $end The end date of the period
     * @param DateInterval $interval The interval between recurrences within the period
     * @return CarbonPeriod
     */
    public function datePeriodFilter($start, $end, $interval = '1 day'): CarbonPeriod {
        if($interval instanceof DateInterval) {
            $interval = CarbonInterval::instance($interval);
        }else{
            $interval = CarbonInterval::createFromDateString($interval);
        }
        return new CarbonPeriod($start, $interval, $end);
    }

    public function dateIntervalToText(DateInterval $interval): string {
        $interval = CarbonInterval::instance($interval);
        return $interval->forHumans();
    }

    /**
     * @param string|DateTime|null
     * @return string
     */
    public function dateDiffForHumans($date): string {
        if($date instanceof DateTime === false){
            $date = Carbon::parse($date);
        }
        $date = Carbon::instance($date);
        return $date->diffForHumans();
    }

    public function carbon($date) : Carbon{
        if($date instanceof \DateTimeInterface){
            return  Carbon::instance($date);
        }

        return Carbon::parse($date);
    }
}