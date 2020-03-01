<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-20
 * Time: 23:55
 */

namespace App\Chart\Type\Bar;


class Bar {
    /**
     * @var float
     */
    private $y;

    public function __construct(float $y) {
        $this->y = $y;
    }

    /**
     * @return float
     */
    public function getY(): float {
        return $this->y;
    }

    /**
     * @param float $y
     */
    public function setY(float $y): void {
        $this->y = $y;
    }


}