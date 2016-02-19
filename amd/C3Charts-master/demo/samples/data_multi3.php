<?php
/* -------------------------------------------------------
 * C3Charts for PHP. Demo site
 *
 * MIT License
 * -------------------------------------------------------
 */

use \Alto\Libs\C3Charts\Chart;
use \Alto\Libs\C3Charts\Column;

$aColumn1 = new Column('data1');
$aColumn2 = new Column('data2');
$aColumn3 = new Column('data3');

$aColumn1->setType('area');
$aColumn2->setType('area-spline');

$oChart = new Chart([$aColumn1, $aColumn2, $aColumn3]);
$aRows = [
    [30, 20, 130],
    [80, 180, 200],
    [190, 240, 100],
    [50, 100, 400],
    [250, 190, 150],
];
foreach($aRows as $aRow) {
    $oChart->addRow($aRow);
}
$oChart->setXTimeSeries(
    [
        '2015-1-1', '2015-1-3', '2015-1-5',
        '2015-1-7', '2015-1-9', '2015-1-11',
    ]
);

// EOF