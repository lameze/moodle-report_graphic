<?php
/* -------------------------------------------------------
 * C3Charts for PHP. Demo site
 *
 * MIT License
 * -------------------------------------------------------
 */

use \Alto\Libs\C3Charts\Chart;
use \Alto\Libs\C3Charts\Column;

$aColumn1 = new Column('data1', [30, 200, 100, 400, 150, 250]);
$aColumn2 = new Column('data2', [20, 180, 240, 100, 190]);
$aColumn3 = new Column('data3', [13, 8, 19, 5, 25, 15, 11]);

$aColumn1->setType('line');
$aColumn2->setType('spline');
$aColumn3->setType('bar')->setY2(true);

$oChart = new Chart([$aColumn1, $aColumn2, $aColumn3]);
$oChart->setY('data1, data2');
$oChart->setY2('data3');

$oChart->setXData(['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']);

// EOF