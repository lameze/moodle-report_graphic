<?php 

// http://c3js.org/samples/chart_bar.html

require(__DIR__ . '/../bootstrap.php'); 

$chart = new Astroanu\C3jsPHP\Chart();

$data = new Astroanu\C3jsPHP\Data();

$bar = new Astroanu\C3jsPHP\Charts\Bar();

$data->setColumns([
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 130, 100, 140, 200, 150, 50]
        ]);

$data->setType(Astroanu\C3jsPHP\Data::TYPE_BAR);

$bar->setWidthRatio('0:5');

$chart->setData($data);
$chart->setBar($bar);

?>
<?php $chart->render('chart', true); ?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="https://rawgit.com/masayuki0812/c3/master/c3.css">
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/c3/0.4.9/c3.min.js"></script>
</head>
<body>
<script type="text/javascript">
$(function(){

	<?php $chart->render(); ?>

});
</script>
<div id="chart"></div>
</body>
</html>