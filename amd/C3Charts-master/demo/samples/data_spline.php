<?php
/* -------------------------------------------------------
 * C3Charts for PHP. Demo site
 *
 * MIT License
 * -------------------------------------------------------
 */

use \Alto\Libs\C3Charts\Chart;

$aData = [
    'data1' => [30, 200, 100, 400, 150, 250],
    'data2' => [20, 180, 240, 100, 190],
];
$oChart = new Chart($aData);
$oChart->setChartType('spline');

// EOF