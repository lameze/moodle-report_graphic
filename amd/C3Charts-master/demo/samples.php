<?php
/* -------------------------------------------------------
 * C3Charts for PHP. Demo site
 *
 * MIT License
 * -------------------------------------------------------
 */

define('ROOT_DIR', __DIR__);
define('ROOT_URL', '//' . $_SERVER['HTTP_HOST'] . '/demo/');

include_once ROOT_DIR . '/../src/autoload.php';

$aDemos = [
    'single' => [
        'title' => 'Single Charts',
        'items' => [
            'line'   => [
                'title' => 'Simple Chart',
                'text' => 'Simple single line chart',
                'link'  => ROOT_URL . 'samples/?demo=line',
                'data'  => 'samples/data_line.php',
            ],
            'spline' => [
                'title' => 'Spline Double Chart',
                'text' => 'Spline chart with two data sequences',
                'link'  => ROOT_URL . 'samples/?demo=spline',
                'data'  => 'samples/data_spline.php',
            ],
            'pie'    => [
                'title'    => 'Pie Charts',
                'text' => 'Pie chart',
                'link'     => ROOT_URL . 'samples/?demo=pie',
                'data'     => 'samples/data_pie.php',
                'template' => '_chart2.tpl',
            ],
            'multi1' => [
                'title' => 'Simple Multi Chart',
                'text' => 'Simple multi chart with several data sequences',
                'link'  => ROOT_URL . 'samples/?demo=multi1',
                'data'  => 'samples/data_multi1.php',
            ],
            'multi2' => [
                'title' => 'Multi Chart',
                'text' => 'Multi chart with several data sequences and seconf Y axis',
                'link'  => ROOT_URL . 'samples/?demo=multi2',
                'data'  => 'samples/data_multi2.php',
            ],
            'multi3' => [
                'title' => 'Multi Chart with additionsl rows',
                'text' => 'Multi chart with several data sequences and additionsl rows',
                'link'  => ROOT_URL . 'samples/?demo=multi3',
                'data'  => 'samples/data_multi3.php',
            ],
        ]
    ],
];

// EOF