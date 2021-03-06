<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-21
 * Time: 02:35
 */

namespace App\Twig;

use App\Chart\Type\Bar\BarChart;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ChartExtension extends AbstractExtension {
    /**
     * @var int
     */
    protected int $colorIndex = 0;

    protected array $colors = ['#A93226', '#2E86C1', '#7D3C98', '#229954', '#F4D03F', '#D35400', '#7F8C8D', '#2E4053'];

    public function getFunctions(): array {
        return [
            new TwigFunction('chart', [$this, 'renderChart'], ['is_safe' => ['html']]),
        ];
    }

    public function renderChart($chart, $type = 'bar'): string {
        if ($chart instanceof BarChart && $type === 'bar') {
            $datasets = $chart->getDatasets();
            $uniqueId = uniqid('chart-', true);

            //Add colors to the dataset
            foreach ($datasets as $dataset){
                $dataset['backgroundColor'] = $this->getColor();
            }

            $labels = json_encode($chart->getLabels(), JSON_THROW_ON_ERROR | true, 512);
            $datasets = json_encode($datasets, JSON_THROW_ON_ERROR | true, 512);

            return '
                <canvas id="'.$uniqueId.'" class="chartjs" width="undefined" height="undefined"></canvas>
                <script>
                    window.addEventListener("load", function () {
                        new Chart(document.getElementById("'.$uniqueId.'"), {
                            type: \'bar\',
                            data: {
                                labels: '.$labels.',
                                datasets: '.$datasets.'
                                
                            }
                        })
                    });

                </script>
                ';
        }
        return '';
    }

    protected function getColor(){
        $color = $this->colorIndex % count($this->colors);
        $this->colorIndex++;
        return $this->colors[$color];
    }
}