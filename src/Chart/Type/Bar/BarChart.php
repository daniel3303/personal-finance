<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-20
 * Time: 23:50
 */

namespace App\Chart\Type\Bar;


use App\Util\Month;
use ArrayObject;

class BarChart {
    public static function createForYear(array $datasets) : self {
        $labels = new ArrayObject();

        foreach (Month::monthsIndexes() as $month) {
           $labels->append(Month::numberToName($month));
        }

        return self::create($labels->getArrayCopy(), $datasets);
    }

    public static function create(array $labels, array $datasets) : BarChart{
        $labels = new ArrayObject($labels);
        $datasets = new ArrayObject($datasets);

        $chart = new self($labels);
        foreach ($datasets as $dataset) {
            foreach ($labels as $label){
                $bar = new Bar(0);
                $chart->addBar($bar, $dataset);
            }
        }
        return $chart;
    }

    /** @var ArrayObject */
    private $datasets;

    /**
     * @var array
     */
    private $labels;

    /**
     * @var int
     */
    private $decimalPlaces;


    public function __construct(ArrayObject $labels) {
        $this->datasets = new ArrayObject();
        $this->labels = $labels;
        $this->decimalPlaces = 0;
    }

    public function getLabels() : array {
        return $this->labels->getArrayCopy();
    }

    /**
     * @return int
     */
    public function getDecimalPlaces(): int {
        return $this->decimalPlaces;
    }

    /**
     * @param int $decimalPlaces
     */
    public function setDecimalPlaces(int $decimalPlaces): void {
        $this->decimalPlaces = $decimalPlaces;
    }


    protected function addBar(Bar $bar, string $dataset) {
        if($this->hasDataset($dataset) === false){
            $this->datasets->append(new ArrayObject(['label' => $dataset, 'data' => new ArrayObject()]));
        }
        $dataset = $this->getDataset($dataset);
        $dataset["data"]->append($bar);
    }

    public function getBarByIndex(string $dataset, int $index) : ?Bar{
        if($this->hasDataset($dataset) === false || isset($this->getDataset($dataset)["data"][$index]) === false){
            return null;
        }
        return $this->getDataset($dataset)["data"][$index];
    }

    public function getPoints(string $dataset) : array {
        if($this->hasDataset($dataset) === false){
            return [];
        }

        $values = [];
        foreach ($this->getDataset($dataset)["data"] as $bar){
            /** @var $bar Bar */
            array_push($values, $bar->getY());
        }
        return $values;
    }

    public function hasLabel(string $label) : bool {
        foreach ($this->labels as $labelI){
            if($label === $labelI){
                return true;
            }
        }
        return false;
    }

    public function hasDataset(string $dataset) : bool {
        return $this->getDataset($dataset) !== null;
    }

    /**
     * @param string $dataset
     * @return Bar[]
     */
    public function getDataset(string $dataset): ?array {
        foreach ($this->datasets as $datasetI){
            if($datasetI["label"] === $dataset){
                return $datasetI->getArrayCopy();
            }
        }
        return null;
    }

    public function getDatasets() : array {
        $datasets = $this->datasets->getArrayCopy();

        foreach ($datasets as $key => $dataset) {
            $data = [];
            foreach ($datasets[$key]["data"] as $bar){
                /** @var $bar Bar */
                array_push($data, round($bar->getY(), $this->getDecimalPlaces()));
            }
            $datasets[$key]["data"] = $data;
        }

        return $datasets;
    }
}