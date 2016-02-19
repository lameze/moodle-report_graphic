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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <img src="assets/logo175x125.png" style="float: right;">
        <h1>C3Charts for PHP</h1>
        <p>PHP classes for C3.js (D3-based reusable chart library)</p>
        <small>Required: PHP 5.4+, <a href="http://d3js.org/">D3.js</a> and
            <a href="http://c3js.org/">C3.js</a> Javascipt library for frontend</small>
        <!-- p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p -->
    </div>
</div>

<div class="container">

    <?php foreach ($aDemos as $aGroup): ?>
    <!-- h3><?php echo $aGroup['title']; ?></h3 -->
    <div class="row">
        <?php foreach ($aGroup['items'] as $aItem): ?>
        <div class="col-md-4">
            <h4><?php echo $aItem['title']; ?></h4>
            <p><?php echo $aItem['text']; ?></p>
            <p><a class="btn btn-default" href="<?php echo ($aItem['link'] ? $aItem['link'] : '#'); ?>" role="button">View demo &raquo;</a></p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>

    <hr>

    <footer>
        <p>&copy; 2015 AltoCMS Team</p>
    </footer>
</div> <!-- /container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>

