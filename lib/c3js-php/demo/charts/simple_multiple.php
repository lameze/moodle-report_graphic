<?php 

// http://c3js.org/samples/simple_multiple.html

require(__DIR__ . '/../bootstrap.php'); 

$chart = new Astroanu\C3jsPHP\Chart();

$data = new Astroanu\C3jsPHP\Data();

$data->setColumns([
            ['data1', 30, 200, 100, 400, 150, 250],
            ['data2', 50, 20, 10, 40, 15, 25]
        ]);

$chart->setData($data);

?>
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