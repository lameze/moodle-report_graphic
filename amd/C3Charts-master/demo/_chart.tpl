<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo ROOT_URL; ?>">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

    <link rel="stylesheet" href="assets/c3js/css/c3.css">

    <!-- highlight.js -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.0.0/styles/default.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        body {
            min-height: 100%;
            padding-top: 70px;
        }
        .label.legend {
            position: relative;
            top: 9px;
            left: 10px;
        }
    </style>
</head>
<body>

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo ROOT_URL; ?>">C3Charts for PHP</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#"><?php echo $sMenuItem; ?></a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>


<div class="container">

    <div class="row">
        <div class="col-md-8">
<!-- [[BEGIN CHART HTML]]-->
<div class='chart'>
    <div id='chart'></div>
</div>
<!-- [[END CHART HTML]]-->
        </div>
        <div class="col-md-4">
            <table class="table">
            </table>
        </div>
    </div>

    <div class="code">
        <div class="label label-default legend">PHP</div>
        <pre><code class="php"><?php echo $sPhpCode; ?></code></pre>
        <div class="label label-default legend">HTML</div>
        <pre><code><?php echo $sHtmlCode; ?></code></pre>
    </div>

</div> <!-- /container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.0.0/highlight.min.js"></script>
<script src="assets/c3js/js/d3.min.js"></script>
<script src="assets/c3js/js/c3.min.js"></script>

<script>
    hljs.initHighlightingOnLoad();
</script>

<!-- [[BEGIN CHART HTML]]-->
<script>
    var chart = c3.generate(<?php echo $oChart; ?>);
</script>
<!-- [[END CHART HTML]]-->

</body>
</html>

